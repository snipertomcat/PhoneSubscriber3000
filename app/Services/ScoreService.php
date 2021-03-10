<?php

namespace Apithy\Services;

use Apithy\Enrollment;
use Apithy\EnrollmentProgress;
use Illuminate\Http\Request;

class ScoreService
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setEnrollmentProgressScore($enrollment_progress_id, $enrollment_id, $internalCall = false)
    {
        $data = [
            'score' => 0,
            'total' => 0,
            'count_activities' => 0,
            'success' => 0
        ];
        try {
            \DB::transaction(function () use ($enrollment_progress_id, $enrollment_id, &$data, $internalCall) {
                $enrollment_progress = EnrollmentProgress::where([
                    ['id', $enrollment_progress_id]
                ])
                    ->firstOrFail();

                if (!$internalCall && $enrollment_progress->status != 3) {
                    return;
                }

                $activities = $enrollment_progress->activities()
                    ->where('is_scoreable', 1)
                    ->get();
                $activities_count = 0;
                $total = 0;
                $score = 0;
                foreach ($activities as $key => $activity) {
                    $activities_count ++;
                    $pivotScore = ((isset($activity->pivot->score)) ? $activity->pivot->score : 0);
                    $total += $pivotScore;
                    $success = (int) ($pivotScore >= 0.6);
                    \DB::table('experience_enrollment_progress_activities')
                        ->where([
                            ['activity_id', $activity->id],
                            ['enrollment_progress_id', $enrollment_progress->id]
                        ])
                        ->update([
                            'success' => $success,
                            'score' => $pivotScore
                        ]);
                }
                if ($total > 0) {
                    $score = $total / $activities_count;
                }
                \DB::table('experience_enrollment_progress')
                    ->where([
                        ['enrollment_id', $enrollment_id],
                        ['session_id', $enrollment_progress->session_id]
                    ])
                    ->update([
                        'score' => $score,
                        'success' => (int) ($score >= 0.6)
                    ]);
                $data = [
                    'score' => $score,
                    'total' => $total,
                    'count_activities' => $activities_count,
                    'success' => (int) ($score >= 0.6)
                ];
            });
            return $data;
        } catch (\Exception $ex) {
            report($ex);
            if ($internalCall) {
                new \Exception($ex->getMessage());
            }
        }
        return $data;
    }

    public function setExperienceScores($experience_id, $enrollment_id, $reeScore = false, $user_id = null)
    {
        try {
            \DB::transaction(function () use ($experience_id, $enrollment_id, $reeScore, $user_id) {
                $enrollment = Enrollment::where([
                    ['id', $enrollment_id],
                    ['experience_id', $experience_id],
                    ['user_id', $this->request->input('user', $user_id)],
                    ['status', Enrollment::ENROLLMENT_STATUS_FINISHED]
                ])
                    ->firstOrFail();

                if (isset($enrollment->score) && !$reeScore) {
                    return;
                }
                $enrollment_progress = $enrollment->progress()
                    ->whereHas('activities', function ($query) {
                        $query->where('is_scoreable', 1);
                    })
                    ->get();

                $enrollment_score = 0;
                $enrollment_progress_count = 0;
                $activity_count = 0;

                foreach ($enrollment_progress as $key => $progress) {
                    $enrollment_progress_count ++;
                    $activityData = $this->setEnrollmentProgressScore($progress->id, $enrollment->id, true);
                    $enrollment_score += $activityData['score'];
                    $activity_count += $activityData['count_activities'];
                }

                $total_score = 0;

                if ($enrollment_score > 0) {
                    $total_score = $enrollment_score / $enrollment_progress_count;
                }

                $enrollment->score = $total_score;
                $enrollment->success = (int) ($total_score >= 0.6 || $activity_count == 0);
                $enrollment->saveOrFail();
            });
        } catch (\Exception $ex) {
            report($ex);
        }
    }
}
