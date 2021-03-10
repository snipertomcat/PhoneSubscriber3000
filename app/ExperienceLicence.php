<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;
use Apithy\Support\Database\CacheQueryBuilder;
use Illuminate\Notifications\Notifiable;

/**
 * Class experienceExecution
 * @package Apithy
 */
class ExperienceLicence extends Model
{

    use CacheQueryBuilder, Notifiable;

    const STATUS_AVAILABLE = 0;
    const STATUS_UNAVAILABLE = 1;
    const STATUS_WAITING_CONFIRMATION = 2;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experiences_licences';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'experience_id' => 'integer',
        'user_id' => 'integer',
        'buyed_by' => 'integer',
        'status' => 'int',
        'transaction_id' => 'integer',
        'expiration_active' => 'integer',
        'expiration_start' => 'datetime',
        'expiration_ends' => 'datetime',
        'day_left' => 'integer'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'experience_id',
        'user_id',
        'buyed_by',
        'status',
        'transaction_id',
        'email',
        'user_purchase_id',
        'expiration_active',
        'expiration_start',
        'expiration_ends',
        'day_left'
    ];


    /**
     * The experience execution has one experience.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }


    /**
     * The experience execution belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class)
            ->where('user_id', $this->user_id)
            ->where('experience_id', $this->experience_id);
    }


    /**
     * The experience execution belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */

    public function buyedBy()
    {
        return $this->belongsTo(User::class, 'buyed_by');
    }


    /**
     * The experience execution belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function routeNotificationForMail()
    {
        $user = $this->user()->first();
        if (isset($user->email)) {
            return $user->email;
        }
        return $this->email;
    }

    public function routeNotificationForSns()
    {
        if ($this->user->contact_preference == "whatsapp") {
            return "whatsapp:+52{$this->user->phone}";
        } else {
            return "+52{$this->user->phone}";
        }
    }

    public function routeNotificationForTwilio()
    {
        if ($this->user->contact_preference == "whatsapp") {
            return "whatsapp:+52{$this->user->phone}";
        } else {
            return "+521{$this->user->phone}";
        }
    }

    public function scopeBuyedLicenses($query)
    {
        $auth = \Auth::user();
        $company_admins = $auth
            ->company()
            ->first()
            ->users()
            ->whereHas('roles', function ($q) {
                $q->where('name', 'like', 'Administrador');
            })
            ->get()
            ->pluck('id');

        return $query
            ->whereIn('buyed_by', $company_admins);
    }
}
