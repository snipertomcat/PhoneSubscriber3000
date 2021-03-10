<?php

namespace Apithy;

use Apithy\Services\EnrollmentService;
use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Apithy\Support\Database\CacheQueryBuilder;
use Illuminate\Notifications\Notifiable;

/**
 * Class Country
 * @package Apithy
 */
class Enrollment extends Model implements Sortable, Filterable
{
    use SortTrait, FilterTrait, CacheQueryBuilder, Notifiable;


    const ENROLLMENT_STATUS_ABANDONED = 0;
    const ENROLLMENT_STATUS_ENROLLED = 1;
    const ENROLLMENT_STATUS_FINISHED = 2;
    const ENROLLMENT_STATUS_SUSPENDED = 3;
    const ENROLLMENT_STATUS_EXPIRED = 4;
    const ENROLLMENT_STATUS_EXPULSED = 5;
    const ENROLLMENT_STATUS_STARTED = 6;
    const ENROLLMENT_STATUS_IN_PROGRESS = 7;
    const ENROLLMENT_STATUS_IN_PROGRESS_EXPERIENCE_UPDATED = 8;
    const ENROLLMENT_STATUS_CANCELED_BY_SESSION_DELETED = 9;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experience_enrollment';

    protected $casts = [
        'score'         => 'decimal:2',
    ];

    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = false;


    /**
     * A country has many companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function experience()
    {
        $relation = $this->belongsTo(Experience::class);

        return $relation;
    }


    /**
     * A country has many companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function progress($session = false)
    {
        $relation = $this->hasMany(EnrollmentProgress::class);

        if ($session) {
            $relation->where('session_id', $session);
        }

        $relation->where('status', '<>', EnrollmentService::SESSION_STATUS_DELETED);

        return $relation;
    }

    /**
     * A country has many companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions($session = false)
    {
        $relation = $this->belongsToMany(Session::class, 'experience_enrollment_progress')
            ->withPivot('status', 'id');

        if ($session) {
            $relation->wherePivot('session_id', $session);
        }

        return $relation;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function tracking()
    {
        $relation = $this->hasManyThrough(
            EnrollmentTracking::class,
            EnrollmentProgress::class,
            'enrollment_id',
            'enrollment_progress_id',
            'id',
            'id'
        );

        return $relation;
    }


    public function licences()
    {
        return $this->hasMany(ExperienceLicence::class)
            ->where('user_id', $this->user_id)
            ->where('experience_id', $this->experience_id);
    }

    public function timeTracking()
    {
        return $this->hasMany(EnrollmentTimeTracking::class, 'enrollment_id', 'id');
    }

    public function getTotalTime()
    {
        $timeData = $this->timeTracking()->sum('time');
        return $timeData;
    }

    public function routeNotificationForSns()
    {
        if ($this->user->contact_preference == "whatsapp") {
            return 'whatsapp:+521' . $this->user->phone;
        } else {
            return '+52' . $this->user->phone;
        }
    }

    public function routeNotificationForMail()
    {
        $user = $this->user()->first();
        if (isset($user->email)) {
            return $user->email;
        }
        return $this->email;
    }

    public function routeNotificationForTwilio()
    {
        if ($this->user->contact_preference == "whatsapp") {
            return 'whatsapp:+521' . $this->user->phone;
        } else {
            return '+521' . $this->user->phone;
        }
    }

    public function getStatusText()
    {
        $status = 'No disponible';
        switch ($this->status) {
            case self::ENROLLMENT_STATUS_FINISHED:
                $status = 'Finalizado';
                break;
            case self::ENROLLMENT_STATUS_IN_PROGRESS:
                $status = 'En progreso';
                break;
            case self::ENROLLMENT_STATUS_FINISHED:
                $status = 'Finalizado';
                break;
            case self::ENROLLMENT_STATUS_ENROLLED:
                $status = 'Por comenzar';
                break;
        }

        return $status;
    }
}
