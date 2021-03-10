<?php

namespace Apithy;

use Apithy\Services\EnrollmentService;
use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Apithy\Support\Database\CacheQueryBuilder;

/**
 * Class Country
 * @package Apithy
 */
class EnrollmentProgress extends Model implements Sortable, Filterable
{
    use SortTrait, FilterTrait, CacheQueryBuilder;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experience_enrollment_progress';

    protected $casts = [
        'score'         => 'decimal:2',
    ];

    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = false;

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




    /**
     * A country has many companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollment($event = false)
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * A country has many companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tracking($event = false)
    {
        $relation = $this->hasMany(EnrollmentTracking::class);

        if ($event) {
            $relation->where('event', $event);
        }
        return $relation;
    }


    public function getGlobalStats()
    {
        $data = $this->tracking()->
        select(["verb", DB::raw("COUNT(verb) as total")])
            ->groupBy("verb")
            ->get()
            ->toArray();

        return $data;
    }

    public function experience()
    {
        return $this->hasManyThrough(
            Experience::class,
            Enrollment::class,
            'experience_id',
            'id',
            'enrollment_id',
            'id'
        );
    }

    public function session()
    {
        return $this->belongsTo(
            Session::class,
            'session_id',
            'id'
        );
    }

    public function activitiesCompleted()
    {
        return $this->hasMany(
            EnrollmentTracking::class,
            'enrollment_progress_id',
            'id'
        )->where('event_type', EnrollmentService::TRACKING_TYPE_CONTENT_FINISHED);
    }


    public function activities()
    {
        return $this->belongsToMany(
            ExperienceActivity::class,
            'experience_enrollment_progress_activities',
            'enrollment_progress_id',
            'activity_id'
        )->withPivot(
            'success',
            'user_response_pattern',
            'correct_response_pattern',
            'score',
            'started_at',
            'finished_at',
            'created_at',
            'updated_at'
        );
    }

    public function timeTracking()
    {
        return $this->hasMany(EnrollmentTimeTracking::class, 'enrollment_progress_id', 'id');
    }

    public function getTotalTime()
    {
        $timeData = $this->timeTracking()->sum('time');
        return $timeData;
    }
}
