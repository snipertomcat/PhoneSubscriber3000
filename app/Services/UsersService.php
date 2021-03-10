<?php

namespace Apithy\Services;

use Apithy\EnrollmentProgress;
use Apithy\EnrollmentTracking;
use Apithy\Experience;
use Apithy\Http\Resources\User\SimpleUserResource;
use Apithy\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UsersService
{
    public function getStatuses()
    {
        return collect([
            ['label' => 'unconfirmed', 'value' => User::STATUS_UNCONFIRMED],
            ['label' => 'active', 'value' => User::STATUS_ACTIVE],
            ['label' => 'deleted', 'value' => User::STATUS_DELETE],
            ['label' => 'suspended', 'value' => User::STATUS_SUSPENDED],
        ]);
    }

    public function activitiesGraphs(User $user, $status = null)
    {
        $experiences = $user->experiencesCompleted();

        if (isset($status)) {
            $experiences = $experiences->where('status', $status);
        }

        $experiences = $experiences->get();

        $sessions = $user->sessionsCompleted()->whereIn('enrollment_id', $experiences->pluck('id'))->get();

        $experiences_completed = [
            'data_list' => $experiences->groupBy(function ($item, $key) {
                return Carbon::parse($item->updated_at)->englishMonth;
            }),
            'total' => count($user->experiencesCompleted)
        ];

        $session_completed = [
            'data_list' => $sessions->groupBy(function ($item, $key) {
                return Carbon::parse($item->updated_at)->englishMonth;
            }),
            'total' => count($user->sessionsCompleted)
        ];

        $activity_completed_status = null; // Todo: Sustituir por el valor del estado de completado de una actividad

        $enrollments_progress_activities = $user
            ->getExperienceProgress()
            ->with('activities')
            ->get()
            ->whereIn('enrollment_id', $experiences->pluck('id'))
            ->pluck('activities')
            ->collapse()
            ->where('status', $activity_completed_status);

        $total = count($enrollments_progress_activities);

        $data_list = $enrollments_progress_activities->groupBy(function ($item, $key) {
            return Carbon::parse($item->pivot->finished_at)->englishMonth;
        });

        $activities_completed = [
            'data_list' => $data_list,
            'total' => $total
        ];

        return [
            'experiences' => $experiences_completed,
            'sessions' => $session_completed,
            'activities' => $activities_completed
        ];
    }

    public function experienceGraphs(User $user, Experience $experience)
    {
        $activities_completed = new Collection();
        $aux = new Collection();
        $array = [];

        $session_completed = [
            'data_list' => $experience->sessionsCompleted($user->id)->get()->groupBy(function ($item, $key) {
                return Carbon::parse($item->updated_at)->englishMonth;
            }),
            'total' => count($experience->sessionsCompleted($user->id)->get())
        ];

        $experience->sessionsCompleted($user->id)->get()->each(function ($session) use ($aux) {
            $aux->push($session->activitiesCompleted);
        });

        $aux->each(function ($activity_array) use ($activities_completed) {
            $activity_array->each(function ($activity) use ($activities_completed) {
                $activities_completed->push($activity);
            });
        });


        $enrollments_progress = $user->getExperienceProgress()
            ->where('experience_enrollment.experience_id', $experience->id)
            ->get()
            ->pluck('id');

        $tracking = EnrollmentTracking::with(['progress.enrollment'])
            ->whereIn('enrollment_progress_id', $enrollments_progress)
            ->where('event_type', 'xapi')
            ->where('verb', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();


        $activities_completed = [
            'data_list' => $tracking->groupBy(function ($item, $key) {
                return Carbon::parse($item->updated_at)->englishMonth;
            }),
            'total' => count($activities_completed)
        ];

        $array['sessions'] = $session_completed;
        $array['activities'] = $activities_completed;

        return $array;
    }

    public function getUsers(Request $request)
    {
        $users = User::with(array('company', 'logins' => function ($query) {
            $query->latest()->first();
        }))
            ->orderBy("created_at", "desc")
            ->Allowed()
            ->SystemVisible();

        if ($request->by_registration_method) {
            $users->where('registration_method', 'like', $request->by_registration_method);
        }

        if ($request->by_status) {
            $users->where('status', $request->by_status[0]);
        }

        if ($request->by_role) {
            $users->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('id', $request->by_role);
            });
        } else {
            $users->with(['roles']);
        }

        if ($request->by_taxonomy) {
            $users->whereHas('taxonomy', function ($query) use ($request) {
                $query->whereIn('id', $request->by_taxonomy);
            })->with('taxonomy');
        } else {
            $users->with('taxonomy');
        }

        return $users;
    }

    public function getFilteredUsers(Request $request)
    {
        $company_id = $request->user()->company->first()->id;
        $users = User::systemVisible()
            ->where('status', '<>', User::STATUS_SUSPENDED)
            ->whereHas('company', function ($query) use ($company_id) {
                $query->where('id', $company_id);
            });
        if ($request->filled('query')) {
            $users->Search($request->input('query'));
        }
        if ($request->filled('experience')) {
            $users->whereDoesntHave('licences', function ($query) use ($request) {
                $query->where('experience_id', $request->input('experience'));
            });
            $users->limit(25);
        }
        return SimpleUserResource::collection($users->get());
    }
}
