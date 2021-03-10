<?php

namespace Apithy\Services;

use Apithy\Enrollment;
use Apithy\EnrollmentTimeTracking;
use Apithy\EnrollmentTracking;
use Apithy\Events\EnrollmentFinished;
use Apithy\Experience;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Apithy\Services\ReactiveService;

/**
 * Class EnrollmentService
 * @package Apithy\Services
 */
class EnrollmentService
{
    const TRACKING_TYPE_XAPI = 'xapi';
    const TRACKING_TYPE_VIDEO = 'video_event';
    const TRACKING_TYPE_CONTENT_FINISHED = 'content_finished';
    const TRACKING_TYPE_CONTENT_SCROLLING = 'content_scrolled';
    const TRACKING_TYPE_CONTENT_ACCESS = 'content_access';
    const TRACKING_TYPE_CONTENT_EVENT = 'content_event';


    //EnrollmentProgress
    const SESSION_STATUS_BLOCKED = 0;
    const SESSION_STATUS_AVAILABLE = 1;
    const SESSION_STATUS_IN_PROGRESS = 2;
    const SESSION_STATUS_FINISHED = 3;
    const SESSION_STATUS_RETRY = 4;
    const SESSION_STATUS_FINISHED_PENDING_ANSWERS = 5;
    const SESSION_STATUS_IN_PROGRESS_BY_UPDATE = 6;
    const SESSION_STATUS_DELETED = 7;
    const SESSION_STATUS_BLOCKED_ADDED_IN_UPDATE = 8;
    const SESSION_STATUS_AVAILABLE_BY_UPDATE = 9;


    //Enrollment
    const ENROLLMENT_STATUS_ABANDONED = 0;
    const ENROLLMENT_STATUS_ENROLLED = 1;
    const ENROLLMENT_STATUS_FINISHED = 2;
    const ENROLLMENT_STATUS_SUSPENDED = 3;
    const ENROLLMENT_STATUS_EXPIRED = 4;
    const ENROLLMENT_STATUS_EXPULSED = 5;
    const ENROLLMENT_STATUS_ACCESS = 6;
    const ENROLLMENT_STATUS_IN_PROGRESS = 7;
    const ENROLLMENT_STATUS_IN_PROGRESS_BY_UPDATE = 8;
    const ENROLLMENT_STATUS_CANCELED_BY_SESSION_DELETED = 9;


    const XAPI_VERB_ATTEMPTED = 'attempted';
    const XAPI_VERB_COMPLETED = 'completed';
    const XAPI_VERB_ANSWERED = 'answered';
    const XAPI_VERB_INTERACTED = 'interacted';

    const VIDEO_STARTED = 'started';
    const VIDEO_SEEKING = 'seeking';
    const VIDEO_PAUSED = 'paused';
    const VIDEO_FINISHED = 'finished';

    const CONTENT_SCROLLED = 'scrolled';
    const CONTENT_ACCESS = 'access';
    const CONTENT_VISIT = 'visit';
    const CONTENT_FINISHED = 'finished';


    public function updateProgress($enrollment_progress, $request)
    {
        if ($request->get("data_type") == EnrollmentService::TRACKING_TYPE_XAPI) {
            $this->handleXAPIEvents($enrollment_progress, $request);
        }

        if ($request->get("data_type") == EnrollmentService::TRACKING_TYPE_VIDEO) {
            $this->handleVideoEvents($enrollment_progress, $request);
        }

        if ($request->get("data_type") == EnrollmentService::TRACKING_TYPE_CONTENT_EVENT
        ) {
            $this->handleContentEvents($enrollment_progress, $request);
        }
    }

    protected function handleContentFinished($enrollment_progress, $request)
    {
        $access_time = $request->get('access_time');

        $experience_enrollment_tracking = [
            'enrollment_progress_id' => $enrollment_progress->id,
            'verb' => self::XAPI_VERB_COMPLETED,
            'access_time' => date("Y-m-d H:i:s", $access_time),
            'event_type' => self::TRACKING_TYPE_CONTENT_FINISHED
        ];

        $experience_enrollment_tracking['created_at'] = Carbon::now();

        $enrollment_experience = DB::table("experience_enrollment")
            ->where('id', $enrollment_progress->enrollment_id)
            ->first();

        $this->updateEnrollmentProgressQuery(
            $enrollment_progress,
            [
                'status' => self::SESSION_STATUS_FINISHED,
                'updated_at' => Carbon::now(),
                'finished_at' => Carbon::now()
            ]
        );

        $this->updateEnrollmentTrackingQuery($enrollment_progress, $experience_enrollment_tracking);
        $this->unlockNextSession($enrollment_progress);
        $this->isExperienceCompleted($enrollment_progress, $enrollment_experience);
    }

    protected function handleContentEvents($enrollment_progress, $request)
    {
        $access_time = $request->get('access_time');
        $event_type = $request->get('data_type');
        $verb = $request->get('verb');

        $experience_enrollment_tracking = [
            'enrollment_progress_id' => $enrollment_progress->id,
            'verb' => $verb,
            'access_time' => date("Y-m-d H:i:s", $access_time),
            'event_type' => $event_type,
            'duration' => 'PT' . (time() - $access_time) . 'S'
        ];

        $experience_enrollment_tracking['created_at'] = Carbon::now();

        $enrollment_experience = DB::table("experience_enrollment")
            ->where('id', $enrollment_progress->enrollment_id)
            ->first();

        if ($enrollment_experience->status != self::ENROLLMENT_STATUS_IN_PROGRESS
            && $enrollment_experience->status != self::ENROLLMENT_STATUS_FINISHED) {
            $this->updateEnrollmentQuery(
                $enrollment_experience,
                [
                    'status' => self::ENROLLMENT_STATUS_IN_PROGRESS,
                    'updated_at' => Carbon::now(),
                    'started_at' => Carbon::now(),
                ]
            );


            if ($enrollment_experience->type == 'inherit') {
                $enrollment_experience_parent = DB::table("experience_enrollment")
                    ->where('id', $enrollment_experience->parent)
                    ->first();


                if ($enrollment_experience_parent) {
                    if ($enrollment_experience_parent->status != self::ENROLLMENT_STATUS_IN_PROGRESS
                        && $enrollment_experience_parent->status != self::ENROLLMENT_STATUS_FINISHED) {
                        $this->updateEnrollmentQuery(
                            $enrollment_experience_parent,
                            [
                                'status' => self::ENROLLMENT_STATUS_IN_PROGRESS,
                                'updated_at' => Carbon::now()
                            ]
                        );
                    }
                }
            }
        }


        $progress_data = [
            'updated_at' => Carbon::now(),
        ];

        if (!$enrollment_progress->started_at) {
            $progress_data['started_at'] = Carbon::now();
        }

        if ($event_type == self::TRACKING_TYPE_CONTENT_EVENT
            && ($verb == self::CONTENT_SCROLLED || $verb == self::CONTENT_ACCESS || $verb == self::CONTENT_VISIT)
            && $enrollment_progress->status != self::SESSION_STATUS_FINISHED) {
            $progress_data['status'] = self::SESSION_STATUS_IN_PROGRESS;
            if (!$enrollment_progress->started_at) {
                $progress_data['started_at'] = Carbon::now();
            }
        } elseif ($event_type == self::TRACKING_TYPE_CONTENT_EVENT && $verb == self::CONTENT_FINISHED) {
            $progress_data['status'] = self::SESSION_STATUS_FINISHED;
            $progress_data['finished_at'] = Carbon::now();
        }


        $this->updateEnrollmentProgressQuery(
            $enrollment_progress,
            $progress_data
        );


        $this->updateEnrollmentTrackingQuery($enrollment_progress, $experience_enrollment_tracking);

        if ($event_type == self::TRACKING_TYPE_CONTENT_EVENT && $verb == self::CONTENT_FINISHED) {
            $this->unlockNextSession($enrollment_progress);
            $this->isExperienceCompleted($enrollment_progress, $enrollment_experience);
        }
    }

    protected function handleXAPIEvents($enrollment_progress, $request)
    {
        $xpi_data = $request->get('data');
        $xapi_verb = $xpi_data['verb']['display']['en-US'];
        $access_time = date("Y-m-d H:i:s", $request->get('access_time'));

        $xpi_data['activity_id'] = $request->get('activity_id', false);

        $enrollment_experience = DB::table("experience_enrollment")
            ->where('id', $enrollment_progress->enrollment_id)
            ->first();

        switch ($xapi_verb) {
            case self::XAPI_VERB_COMPLETED:
                /*
                $this->updateEnrollmentProgressQuery(
                    $enrollment_progress,
                    [
                        'status' => self::SESSION_STATUS_FINISHED,
                        'updated_at' => Carbon::now()
                    ]
                );
                */

                $this->trackXAPIEvent(
                    $enrollment_progress,
                    $xpi_data,
                    $access_time
                );

                //$this->unlockNextSession($enrollment_progress);
                //$this->isExperienceCompleted($enrollment_progress, $enrollment_experience);
                break;

            case self::XAPI_VERB_ANSWERED:
            case self::XAPI_VERB_INTERACTED:
            case self::XAPI_VERB_ATTEMPTED:
            default:
                /*
                if ($enrollment_experience->status == self::ENROLLMENT_STATUS_ENROLLED) {
                    $this->updateEnrollmentQuery(
                        $enrollment_experience,
                        [
                            'status' => self::ENROLLMENT_STATUS_IN_PROGRESS,
                            'updated_at' => Carbon::now()
                        ]
                    );
                }

                if ($enrollment_progress->status == self::SESSION_STATUS_AVAILABLE) {
                    $this->updateEnrollmentProgressQuery(
                        $enrollment_progress,
                        [
                            'status' => self::SESSION_STATUS_IN_PROGRESS,
                            'updated_at' => Carbon::now()
                        ]
                    );
                }
                */

                $this->trackXAPIEvent(
                    $enrollment_progress,
                    $xpi_data,
                    $access_time
                );
                break;
        }
    }

    protected function handleVideoEvents($enrollment_progress, $request)
    {
        $video_event = $request->get('data');
        $access_time = $request->get('access_time');

        $experience_enrollment_tracking = [
            'enrollment_progress_id' => $enrollment_progress->id,
            'verb' => $video_event,
            'access_time' => date("Y-m-d H:i:s", $access_time),
            'event_type' => self::TRACKING_TYPE_VIDEO
        ];

        $experience_enrollment_tracking['created_at'] = Carbon::now();

        switch ($video_event) {
            case self::VIDEO_STARTED:
                $enrollment_experience = DB::table("experience_enrollment")
                    ->where('id', $enrollment_progress->enrollment_id)
                    ->first();


                if ($enrollment_experience->status == self::ENROLLMENT_STATUS_ENROLLED) {
                    $this->updateEnrollmentQuery(
                        $enrollment_experience,
                        [
                            'status' => self::ENROLLMENT_STATUS_IN_PROGRESS,
                            'updated_at' => Carbon::now()
                        ]
                    );
                }

                if ($enrollment_progress->status == self::SESSION_STATUS_AVAILABLE) {
                    $this->updateEnrollmentProgressQuery(
                        $enrollment_progress,
                        [
                            'status' => self::SESSION_STATUS_IN_PROGRESS,
                            'updated_at' => Carbon::now()
                        ]
                    );
                }

                break;
            case self::VIDEO_PAUSED:
            case self::VIDEO_SEEKING:
                break;
            case self::VIDEO_FINISHED:
                $this->updateEnrollmentProgressQuery(
                    $enrollment_progress,
                    [
                        'status' => self::SESSION_STATUS_FINISHED,
                        'updated_at' => Carbon::now()
                    ]
                );

                $enrollment_experience = DB::table("experience_enrollment")
                    ->where('id', $enrollment_progress->enrollment_id)
                    ->first();

                $this->unlockNextSession($enrollment_progress);
                $this->isExperienceCompleted($enrollment_progress, $enrollment_experience);
                break;
        }

        $this->updateEnrollmentTrackingQuery($enrollment_progress, $experience_enrollment_tracking);
    }

    protected function updateEnrollmentTrackingQuery($enrollment_progress, $data)
    {
        DB::table("experience_enrollment_tracking")
            ->where('id', $enrollment_progress->id)
            ->insert(
                $data
            );
    }

    protected function updateEnrollmentProgressQuery($enrollment_progress, $data)
    {
        DB::table("experience_enrollment_progress")
            ->where('id', $enrollment_progress->id)
            ->update(
                $data
            );
    }

    protected function updateEnrollmentQuery($enrollment_experience, $data)
    {
        DB::table("experience_enrollment")
            ->where('id', $enrollment_experience->id)
            ->update(
                $data
            );
    }

    protected function trackXAPIEvent($enrollment_progress, $data, $access_time)
    {
        $experience_enrollment_tracking = [
            'enrollment_progress_id' => $enrollment_progress->id,
            'verb' => $data['verb']['display']['en-US'],
            'object' => $data['object']['id'],
            'access_time' => $access_time,
            'event_type' => self::TRACKING_TYPE_XAPI,
        ];

        $experience_enrollment_activity = [
            'started_at' => $access_time,
            'finished_at' => Carbon::now(),
        ];


        if ($data['activity_id']) {
            $experience_enrollment_tracking['activity_id'] = $data['activity_id'];
        }

        if (isset($data['result'])) {
            $experience_enrollment_tracking["results_json"] = json_encode($data['result']);
            if (isset($data['result']['duration'])) {
                $experience_enrollment_tracking["duration"] = $data['result']['duration'];
            }
            if (isset($data['result']['score'])) {
                $experience_enrollment_tracking["min_score"] = $data['result']['score']['min'];
                $experience_enrollment_tracking["max_score"] = $data['result']['score']['max'];
                $experience_enrollment_tracking["raw_score"] = $data['result']['score']['raw'];
                $experience_enrollment_tracking["scaled_score"] = $data['result']['score']['scaled'];
                $experience_enrollment_tracking["score"] = $data['result']['score']['scaled'];

                $experience_enrollment_activity["score"] = $data['result']['score']['scaled'];
            }

            if (isset($data['result']['success'])) {
                $experience_enrollment_tracking["success"] = $data['result']['success'];
                $experience_enrollment_activity["success"] = $data['result']['success'];
            }
        }

        $experience_enrollment_tracking['created_at'] = Carbon::now();

        if ($experience_enrollment_tracking['verb'] == self::XAPI_VERB_COMPLETED ||
            isset($data['result']['completion'])) {
            $completed_verb = DB::table("experience_enrollment_tracking")
                ->where('enrollment_progress_id', $enrollment_progress->id)
                ->where('verb', self::XAPI_VERB_COMPLETED)
                ->where('access_time', $access_time)
                ->get();

            if (count($completed_verb)) {
                $experience_enrollment_tracking['updated_at'] = Carbon::now();
                DB::table("experience_enrollment_tracking")
                    ->where('id', $enrollment_progress->id)
                    ->where('verb', self::XAPI_VERB_COMPLETED)
                    ->where('access_time', $access_time)
                    ->update($experience_enrollment_tracking);
            } else {
                DB::table("experience_enrollment_tracking")
                    ->where('id', $enrollment_progress->id)
                    ->insert(
                        $experience_enrollment_tracking
                    );

                DB::table("experience_enrollment_progress_activities")
                    ->where('enrollment_progress_id', $enrollment_progress->id)
                    ->where('activity_id', $data['activity_id'])
                    ->update(
                        $experience_enrollment_activity
                    );
            }
        } else {
            if (isset($data['library_data'])) {
                if ($data['library_data']['machineName'] == "apithy.SendText") {
                    $experience_enrollment_activity['user_response_pattern'] =
                        $data['user_response']['activity_info']['user_input'];

                    DB::table("experience_enrollment_progress_activities")
                        ->where('enrollment_progress_id', $enrollment_progress->id)
                        ->where('activity_id', $data['activity_id'])
                        ->updateOrInsert(
                            $experience_enrollment_activity
                        );
                }
            }


            DB::table("experience_enrollment_tracking")
                ->where('id', $enrollment_progress->id)
                ->insert(
                    $experience_enrollment_tracking
                );
        }
        if (isset($data['library_data']['machineName'])) {
            if ($data['library_data']['machineName'] == "apithy.SendText") {
                $this->handleUserSendTextResponse($experience_enrollment_tracking);
            }
        }
    }

    private function sessionList()
    {
        $experience = Experience::findOrFail(request()->input('experience'));
        $sessions = $experience->sessions->map(function ($item, $key) {
            return [
                'id' => $item['id'],
                'weight' => $item['weight']
            ];
        });
        return $sessions;
    }

    private function getNextId($current_id)
    {
        $sessions = $this->sessionList();
        $sort = $sessions->sortBy('weight');
        $length = $sessions->count() - 1;
        $index = 0;
        foreach ($sort as $key => $session) {
            if ($session['id'] == $current_id) {
                if ($key == $length) {
                    $index = $key;
                } else {
                    $index = $key + 1;
                }
            }
        }
        return $sort[$index];
    }

    protected function unlockNextSession($enrollment_progress)
    {
        $next_session = $this->getNextId(request()->input('session'));

        $progress = DB::table("experience_enrollment_progress")
            ->where([
                ['enrollment_id', $enrollment_progress->enrollment_id],
                ['session_id', $next_session['id']]
            ])
            ->get();

        /*
        $progress = DB::table("experience_enrollment_progress")
            ->where('id', '>', $enrollment_progress->id)
            ->take(1)
            ->orderBy('id', 'asc')
            ->get();
        */

        if ($progress->count()) {
            if ($progress[0]->status != self::SESSION_STATUS_FINISHED) {
                DB::table("experience_enrollment_progress")
                    ->where([
                        ['enrollment_id', $enrollment_progress->enrollment_id],
                        ['session_id', $next_session['id']]
                    ])
                    ->update(
                        [
                            'status' => self::SESSION_STATUS_AVAILABLE,
                            'updated_at' => Carbon::now()
                        ]
                    );
                /*
                DB::table("experience_enrollment_progress")
                    ->where('id', '>', $enrollment_progress->id)
                    ->take(1)
                    ->orderBy('id', 'asc')
                    ->update(
                        [
                            'status' => self::SESSION_STATUS_AVAILABLE,
                            'updated_at' => Carbon::now()
                        ]
                    );
                */
            }

            unset($progress);
        }
    }

    protected function isExperienceCompleted($enrollment_progress, $enrollment_experience)
    {

        $is_finished = true;
        $is_finished_journey = true;

        $sessions = DB::table("experience_enrollment_progress")
            ->where("enrollment_id", $enrollment_progress->enrollment_id)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($sessions as $session) {
            if ($session->status != self::SESSION_STATUS_FINISHED) {
                $is_finished = false;
            }
        }

        if ($is_finished) {
            $this->updateEnrollmentQuery(
                $enrollment_experience,
                [
                    'status' => self::ENROLLMENT_STATUS_FINISHED,
                    'updated_at' => Carbon::now(),
                    'finished_at' => Carbon::now()
                ]
            );


            $reactive_service = new ReactiveService(new Request());
            $enrollment = Enrollment::find($enrollment_progress->enrollment_id);

            if ($enrollment) {
                $reactive_service->saveEnrollmentScore(
                    $enrollment_progress->enrollment_id,
                    $enrollment->experience_id,
                    $enrollment->user_id
                );
            }

            event(new EnrollmentFinished($enrollment));
        }

        if ($enrollment_experience->parent) {
            $inherit_enrollments = DB::table("experience_enrollment")
                ->where("parent", $enrollment_experience->parent)
                ->orderBy('id', 'asc')
                ->get();

            foreach ($inherit_enrollments as $child) {
                if ($child->status != self::ENROLLMENT_STATUS_FINISHED) {
                    $is_finished_journey = false;
                }
            }

            if ($is_finished_journey) {
                $this->updateEnrollmentQuery(
                    Enrollment::find($enrollment_experience->parent),
                    [
                        'status' => self::ENROLLMENT_STATUS_FINISHED,
                        'updated_at' => Carbon::now()
                    ]
                );
            }
        }
    }

    /*
     *
     * Add The Status clasess to the enrollment progress in a Session
     *
     * */

    public function setSessionClasess(&$current_enrollment_progress)
    {
        switch ($current_enrollment_progress['status']) {
            case EnrollmentService::SESSION_STATUS_BLOCKED:
                $current_enrollment_progress['status_text'] = "Bloqueado";
                $current_enrollment_progress['status_text_color'] = "gray";
                $current_enrollment_progress['status_text_class'] = "fa-lock";
                break;
            case EnrollmentService::SESSION_STATUS_AVAILABLE:
                $current_enrollment_progress['status_text'] = "Disponible";
                $current_enrollment_progress['status_text_class'] = "fa-lock-open";
                break;
            case EnrollmentService::SESSION_STATUS_IN_PROGRESS:
                $current_enrollment_progress['status_text'] = "En progreso";
                $current_enrollment_progress['status_text_color'] = "blue";
                $current_enrollment_progress['status_text_class'] = "fa-play";

                break;
            case EnrollmentService::SESSION_STATUS_FINISHED:
                $current_enrollment_progress['status_text'] = "Completado";
                $current_enrollment_progress['status_text_color'] = "green";
                $current_enrollment_progress['status_text_class'] = "fa-check-circle";

                break;

            case EnrollmentService::SESSION_STATUS_RETRY:
                $current_enrollment_progress['status_text'] = "orange";
                $current_enrollment_progress['status_text_color'] = "red";
                $current_enrollment_progress['status_text_class'] = "fa-check-circle";
                break;

            case EnrollmentService::SESSION_STATUS_FINISHED_PENDING_ANSWERS:
                $current_enrollment_progress['status_text'] = "Respuestas no enviadas";
                $current_enrollment_progress['status_text_color'] = "yellow";
                $current_enrollment_progress['status_text_class'] = "fa-play";

                break;
        }
    }

    /*
    *
    * Add The Status clasess to the enrollment in a Experience
    *
    * */

    public function setEnrollmentClasess(&$current_enrollment_progress)
    {
        switch ($current_enrollment_progress['status']) {
            case EnrollmentService::ENROLLMENT_STATUS_ENROLLED:
                $current_enrollment_progress['status_text'] = "Enrolado";
                $current_enrollment_progress['status_text_color'] = "gray";
                $current_enrollment_progress['status_text_class'] = "fa-lock";
                break;

            case EnrollmentService::ENROLLMENT_STATUS_IN_PROGRESS:
                $current_enrollment_progress['status_text'] = "En progreso";
                $current_enrollment_progress['status_text_color'] = "blue";
                $current_enrollment_progress['status_text_class'] = "fa-play";

                break;
            case EnrollmentService::ENROLLMENT_STATUS_FINISHED:
                $current_enrollment_progress['status_text'] = "Completado";
                $current_enrollment_progress['status_text_color'] = "green";
                $current_enrollment_progress['status_text_class'] = "fa-check-circle";
                break;
        }
    }

    /*
     *
     * Add The enrollment progress object in a session
     *
     * */

    public function sessionProgress($enrollment_progress, &$experience, $inherit = 'false', &$journey = false)
    {
        //$user_progress = $enrollment_progress->progress()->with(['tracking'])->get();
        $user_progress = $enrollment_progress->progress;

        $current_session = false;
        if ($user_progress) {
            foreach ($experience->sessions as $index => $session) {
                $current_enrollment_progress = $user_progress
                    ->where('session_id', $session->id)
                    ->first();

                if (isset($current_enrollment_progress)) {
                    $current_enrollment_progress = $current_enrollment_progress->toArray();

                    $this->setSessionClasess($current_enrollment_progress);

                    $experience->sessions[$index]->current_enrollment_progress = $current_enrollment_progress;
                    $experience->sessions[$index]->active_class = '';

                    $experience->sessions[$index]->prev_session = (!!$session->prevSession)
                        ? $session->prevSession->id : null;
                    $experience->sessions[$index]->next_session = (!!$session->nextSession)
                        ? $session->nextSession->id : null;
                }

                foreach ($session->childs as $index2 => $child) {
                    $current_enrollment_progress = $user_progress
                        ->where('session_id', $child->id)
                        ->first();

                    if (isset($current_enrollment_progress)) {
                        $current_enrollment_progress = $current_enrollment_progress->toArray();
                        $experience->sessions[$index]->childs[$index2]->current_enrollment_progress = $current_enrollment_progress;
                    }
                }
            }

            if ($experience->type == Experience::TYPE_ADVENTURE) {
                //First Try to get the last in progress Session and later the las available session
                $current_session_progress = $enrollment_progress->progress()
                    ->where('status', EnrollmentService::SESSION_STATUS_IN_PROGRESS)
                    ->orderBy('id', 'DESC')->first();

                if (!$current_session_progress) {
                    $current_session_progress = $enrollment_progress->progress()
                        ->where('status', EnrollmentService::SESSION_STATUS_AVAILABLE)->orderBy('id', 'DESC')->first();
                    if ($current_session_progress) {
                        $current_session = $current_session_progress->session;
                    }
                } else {
                    $current_session = $current_session_progress->session;
                }

                if ($current_session_progress) {
                    $this->setSessionClasess($current_session_progress);
                    $current_session->current_enrollment_progress = $current_session_progress->toArray();
                }


                if ($journey && $current_session) {
                    $journey->current_session = $current_session;
                    $journey->current_experience = $current_session->experience;
                } else {
                    $experience->current_session = $current_session;
                }
            }
        }
    }

    public function handleUserSendTextResponse($experience_enrollment_tracking)
    {
        $et = EnrollmentTracking::where([
            ['enrollment_progress_id', $experience_enrollment_tracking['enrollment_progress_id']],
            ['activity_id', $experience_enrollment_tracking['activity_id']]
        ])
            ->first();
        if (!isset($et)) {
            return;
        }
        $notification = app(\Apithy\Notifications\ActivityWaitScore::class);
        $et->notify($notification);
    }

    public function handleExperienceEdition($experience, $content_data)
    {
    }

    public function updateTime($request)
    {
        $enrollment_tracking=EnrollmentTimeTracking::where('access_time',date('Y-m-d h:i:s',request()->access_time))
        ->where('enrollment_progress_id',request()->enrollment_progress)->first();

        if($enrollment_tracking){
            if($enrollment_tracking->time != (int) request()->time){
                    $enrollment_tracking->time=request()->time;
                    $enrollment_tracking->save();
            }
        }else{
            $enrollment_tracking= new EnrollmentTimeTracking();
            $enrollment_tracking->time=request()->time;
            $enrollment_tracking->access_time=date('Y-m-d h:i:s',request()->access_time);
            $enrollment_tracking->enrollment_id=request()->enrollment_id;
            $enrollment_tracking->enrollment_progress_id=request()->enrollment_progress;
            $enrollment_tracking->save();
        }

        return $enrollment_tracking;
    }
}
