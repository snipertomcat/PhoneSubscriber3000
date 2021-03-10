<?php

namespace Apithy;

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
class EnrollmentTracking extends Model implements Sortable, Filterable
{
    use SortTrait, FilterTrait, CacheQueryBuilder, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experience_enrollment_tracking';

    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = false;

    public $appends = [
        'session_id'
    ];

    public function progress()
    {
        return $this->hasOne(EnrollmentProgress::class, 'id', 'enrollment_progress_id');
    }

    public function getSessionIdAttribute()
    {
        if ($this->hasProgress()) {
            return $this->progress()->first()->session_id;
        }
    }

    public function hasProgress()
    {
        return $this->progress()->count();
    }

    public function activity()
    {
        return $this->belongsTo(ExperienceActivity::class);
    }

    // Functions
    public function getAdmin()
    {
        $user = $this->progress->enrollment->user;
        return User::RoleIn(9)->CompanyIn($user->company[0]->id)->first();
    }

    // Notifications

    public function routeNotificationForSns()
    {
        $user = $this->getAdmin();
        if ($user->contact_preference == "whatsapp") {
            return 'whatsapp:+521' . $user->phone;
        } else {
            return '+521' . $user->phone;
        }
    }

    public function routeNotificationForMail()
    {
        $user = $this->getAdmin();
        return $user->email;
    }

    public function routeNotificationForTwilio()
    {
        $user = $this->getAdmin();
        if ($user->contact_preference == "whatsapp") {
            return 'whatsapp:+521' . $user->phone;
        } else {
            return '+52' . $user->phone;
        }
    }
}
