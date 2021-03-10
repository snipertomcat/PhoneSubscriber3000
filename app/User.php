<?php

namespace Apithy;

use Apithy\Notifications\MailResetPassword;
use Apithy\Services\EnrollmentService;
use Apithy\Services\SmsService;
use Apithy\Utilities\Master\Master;
use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lab404\AuthChecker\Interfaces\HasLoginsAndDevicesInterface;
use Lab404\AuthChecker\Models\HasLoginsAndDevices;
use Lab404\Impersonate\Models\Impersonate as ImpersonateTrait;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Apithy\Support\Database\CacheQueryBuilder;

/**
 * Class User
 * @package Apithy
 */
class User extends Authenticatable implements Sortable, Filterable, Searchable, HasLoginsAndDevicesInterface
{
    use Notifiable, HasApiTokens, SortTrait, FilterTrait, ImpersonateTrait, HasLoginsAndDevices, CacheQueryBuilder;


    /*
     *  User Account STATUS
     */
    // Usario sin confirmar
    const STATUS_UNCONFIRMED = 0;
    // Usuario operativo
    const STATUS_ACTIVE = 1;
    // Usario eliminado
    const STATUS_DELETE = 2;
    // Usuario suspendido
    const STATUS_SUSPENDED = 3;
    // Usuario en espera de cambiar contraseña
    const STATUS_SET_PASSWORD = 4;
    // Usuario con contraseña confirmada
    const STATUS_PASSWORD_ESTABLISHED = 5;

    /**
     * Registration method LMS key name.
     */
    const REGISTRATION_LMS = 'lms';

    /**
     * Registration method Invitation key name.
     */
    const REGISTRATION_INVITATION = 'invitation';

    /*
     *  Registration method Invitation key name.
    */
    const REGISTRATION_ADMIN = 'admin';

    /*
     *  Registration method Invitation key name.
    */

    const REGISTRATION_ON_SITE = 'on_site';

    /*
     *  Registration method Invitation key name.
    */

    const REGISTRATION_IMPORT_FILE = 'import_file';

    /*
     *  Experience enrolled status.
     */
    const USER_ENROLLMENT_STATUS_ABANDONED = 0;
    const USER_ENROLLMENT_STATUS_ENROLLED = 1;
    const USER_ENROLLMENT_STATUS_FINISHED = 2;
    const USER_ENROLLMENT_STATUS_SUSPENDED = 3;
    const USER_ENROLLMENT_STATUS_EXPIRED = 4;
    const USER_ENROLLMENT_STATUS_EXPULSED = 5;
    const USER_ENROLLMENT_STATUS_STARTED = 6;

    /*
     *  Contact preference
     */
    const CONTACT_BY_SMS = 'sms';
    const CONTACT_BY_EMAIL = 'mail';
    const CONTACT_BY_WHATSAPP = 'whatsapp';

    /**
     * Getting Started Status
     */
    const UNFINISHED_GETTING_STARTED = 0;
    const FINISHED_GETTING_STARTED = 1;
    const ACCESS_GETTING_STARTED = 2;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'access' => 'string',
        'password' => 'string',
        'name' => 'string',
        'surname' => 'string',
        'email' => 'string',
        'gender' => 'string',
        'bio' => 'string',
        'banned' => 'boolean',
        'activated' => 'boolean',
        'registration_method' => 'string',
        'avatar' => 'string',
        'status' => 'integer',
        'getting_started_progress' => 'integer',
        'getting_started_status' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birthday',
        'created_at',
        'updated_at',
        'accepted_privacy_terms_at',
        'accepted_service_clients_conditions_at',
        'accepted_colaboration_conditions_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access',
        'password',
        'name',
        'surname',
        'email',
        'birthday',
        'gender',
        'bio',
        'phone',
        'activated',
        'registration_method',
        'avatar',
        'academic_history',
        'work_history',
        'career_title',
        'contact_preference',
        'status',
        'accepted_privacy_terms_at',
        'accepted_service_clients_conditions_at',
        'accepted_colaboration_conditions_at',
        'getting_started_progress',
        'getting_started_status',
        'personal_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'banned',
        'password',
        'remember_token',
    ];

    protected $appends = [
        'level_string_code',
        'full_path_avatar',
        'full_name',
        'enrollment_status_text',
        'enrollment_status_color',
        'is_admin',
        'is_super',
        'is_partner',
        'is_store_user',
        'can_be_impersonated',
        'can_impersonate',
        'is_impersonated',
        'company_system',
        'company_id',
    ];

    /**
     * The attributes that can be sorted.
     *
     * @var array
     */
    protected $sortable = [
        'id',
        'access',
        'name',
        'surname',
        'email',
        'birthday',
        'gender',
        'bio',
        'registration_method',
        'avatar',
        'banned',
        'status',
        'activated',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that can be filtered.
     *
     * @var array
     */
    protected $filterable = [
        'id',
        'access',
        'name',
        'surname',
        'email',
        'birthday',
        'gender',
        'bio',
        'phone',
        'registration_method',
        'avatar',
        'banned',
        'status',
        'activated',
        'created_at',
        'updated_at',
        'personal_code'
    ];

    /**
     * The methods that can be filtered.
     *
     * @var array
     */
    protected $filterableCustom = [
        'roleIn',
    ];

    /**
     * @inheritdoc
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('access', 'like', '%' . $term . '%')
                ->orWhere('name', 'like', '%' . $term . '%')
                ->orWhere('surname', 'like', '%' . $term . '%')
                ->orWhere('email', 'like', '%' . $term . '%')
                ->orWhere('phone', 'like', '%' . $term . '%')
                ->orWhere('personal_code', 'like', '%' . $term . '%')
                ->orWhere('registration_method', 'like', '%' . $term . '%')
                // This is for search by the user's full name
                ->orWhere(\DB::raw("CONCAT(name,' ',surname)"), 'like', "%{$term}%");
        });
    }


    /**
     * Scope the query to fetch the users in base a user Role.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeAllowed($query)
    {


        $auth = Auth::user();
        if ($auth->can('fetch', User::class)) {
            if (!$auth->isSuper()) {
                $companyId = $auth->company[0]->id;

                $query->whereHas('company', function ($q) use ($companyId) {
                    $q->where('id', $companyId);
                })->where('id', '<>', $auth->id);
            }
        }
        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
    }


    public function scopeSystemVisible($query)
    {
        $query->where("status", "<>", self::STATUS_DELETE);
        return $query;
    }

    /**
     * Scope the query by the role id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $roleId
     */
    public function scopeRoleIn($query, $roleId)
    {
        $query->whereHas('roles', function ($q) use ($roleId) {
            $q->where('id', $roleId);
        });
    }

    /**
     * Scope the query by the company id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $roleId
     */
    public function scopeCompanyIn($query, $companyId)
    {
        $query->whereHas('companies', function ($q) use ($companyId) {
            $q->where('id', $companyId);
        });
    }

    /**
     * Get all of the user's metadata.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metadata()
    {
        return $this->morphMany(Metadata::class, 'metadatable');
    }

    /**
     * The Companies that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function company()
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }

    /**
     * The Companies that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }

    /**
     * The roles that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * A User has many settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function settings()
    {
        return $this->hasMany(Settings::class, 'user_id', 'id');
    }

    /**
     * Return user's position.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function position()
    {
        return $this->belongsToMany(CompanyPosition::class, 'company_position_user');
    }

    public function taxonomy()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_users');
    }

    public function taxonomyArea()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_users')
            ->where('type', '=', 'company_area');
    }

    public function taxonomyPosition()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_users')
            ->where('type', '=', 'company_position');
    }

    public function taxonomyTags()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_users')
            ->where('type', '=', 'tag');
    }

    public function taxonomyAbility()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_users')
            ->where('type', '=', 'ability');
    }

    public function taxonomyTeams()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_users')
            ->where('type', '=', 'teams');
    }

    public function taxonomyCertifications()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_users')
            ->where('type', '=', 'certifications');
    }

    public function taxonomyCustoms()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_users')
            ->where('type', '=', 'custom');
    }

    public function evaluationUser()
    {
        return $this->hasMany(EvaluationUser::class, 'evaluation_user');
    }

    /**
     * Return the user's experiences.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function enrollments($enrollment_type = 'primary', $experience_type = false)
    {
        $relation = $this->belongsToMany(Experience::class, 'experience_enrollment')
            ->where('experience_enrollment.type', $enrollment_type)
            ->withPivot('status', 'experience_assignation_id', 'id', 'created_at');

        if ($experience_type) {
            $relation->join(
                'experiences as ex',
                'ex.id',
                '=',
                'experience_enrollment.experience_id'
            )->where('ex.type', $experience_type);
        }

        return $relation;
    }


    /**
     * Return the user's payments methods.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function paymentInformation($provider = false)
    {
        $relation = $this->hasMany(PaymentInformation::class, 'user_id');

        if ($provider) {
            $relation->where("provider", $provider);
        }

        return $relation;
    }


    /**
     * Return the user enrollment for a expecific experience.
     * @param bool $experience
     * @param bool $status
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function experienceEnrollments($experience = false, $status = false, $type = Experience::TYPE_PRIMARY)
    {
        $relation = $this->hasMany(Enrollment::class, 'user_id');

        if ($experience) {
            $relation->where("experience_id", '=', $experience->id);
        }

        if ($status) {
            $relation->where("status", $status);
        }

        $relation->where('experience_enrollment.type', $type);

        return $relation;
    }

    public function transactions($payment_source = false)
    {
        $relation = $this->hasMany(Transaction::class, 'user_id');

        if ($payment_source) {
            $relation->where('user_payment_information_id', $payment_source->id);
        }

        return $relation;
    }

    /**
     * Modifies or creates a new metadata relationship with the given key and value.
     *
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function metadataFor($key, array $value)
    {
        $meta = $this->metadata()->firstOrNew(['key' => $key]);
        $meta->value = $value;
        $meta->save();
        return $this;
    }

    /**
     * Return full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return sprintf('%s %s', $this->name, $this->surname);
    }

    /**
     * Return level code.
     *
     * @return string
     */
    public function getLevelStringCodeAttribute()
    {
        $lut = [
            '001' => 'KIN',
            '002' => 'PRI',
            '003' => 'SEC',
        ];
        $code = $this->level
            ? $this->level->code
            : null;

        return array_key_exists($code, $lut)
            ? $lut[$code]
            : '';
    }

    /**
     * Return full path avatar.
     *
     * @return string
     */
    public function getFullPathAvatarAttribute()
    {
        return $this->avatarUrl();
    }

    /**
     * Verifies if an avatar is present.
     *
     * @return bool
     */
    public function hasAvatar()
    {
        return !empty($this->attributes['avatar']);
    }

    /**
     * Verify if the user has super role.
     *
     * @return bool
     */
    public function isSuper()
    {
        return !!$this->roles->contains('super', true);
    }

    public function getIsSuperAttribute()
    {
        return $this->isSuper();
    }

    /**
     * Verify if the user has admin role.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return !!$this->roles->contains('name', 'Administrador');
    }

    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

    public function getIsStoreUser()
    {
        if ($this->company[0]->id !== Company::getDefautCompanyId()) {
            return false;
        }


        return !!$this->roles->contains('code', 'STU', true);
    }

    public function getIsStoreUserAttribute()
    {
        return $this->getIsStoreUser();
    }

    /**
     * Verify if the user has partner role.
     *
     * @return bool
     */
    public function isPartner()
    {
        return !!$this->roles->contains(Role::PARTNER);
    }

    public function getIsPartnerAttribute()
    {
        return $this->isPartner();
    }

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        // For example
        return $this->isSuper() || $this->isAdmin();
    }

    public function getCanImpersonateAttribute()
    {
        return $this->canImpersonate();
    }

    /**
     * @return bool
     */

    public function canBeImpersonated()
    {
        if (!$this->isSuper()) {
            return true;
        }
    }

    public function getCanBeImpersonatedAttribute()
    {
        return $this->canBeImpersonated();
    }

    public function getIsImpersonatedAttribute()
    {
        return $this->isImpersonated();
    }

    /**
     * Get url for avatar image, if there is none the gravatar version
     * is returned.
     *
     * @return string
     */
    public function avatarUrl()
    {
        if ($this->hasAvatar()) {
            return Storage::url($this->attributes['avatar'])."?time=".time();
        }

        if (isset($this->attributes['email'])) {
            $hash = md5(strtolower(trim($this->attributes['email'])));
        } else {
            $hash = md5(strtolower(trim('help@apithy.com')));
        }

        return sprintf('https://www.gravatar.com/avatar/%s?d=mp&size=512', $hash);
    }

    /**
     * Check if the registration method match.
     *
     * @param string $method
     * @return bool
     */
    public function registrationMethodIs($method)
    {
        return trim($this->attributes['registration_method']) == trim($method);
    }

    /**
     * Check if the given password is the same.
     *
     * @param string $password
     * @return bool
     */
    public function confirmPassword($password)
    {
        return Hash::check($password, $this->attributes['password']);
    }

    /**
     * Set password hash without saving.
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->attributes['password'] = bcrypt($password);
        return $this;
    }

    public function hasCompany()
    {
        return !empty($this->company()->first());
    }

    public function hasRoles()
    {
        return !empty($this->roles()->first());
    }

    public function belongsToCompany($model)
    {
        if ($model instanceof Company) {
            return ($this->company()->first()->id == $model->id);
        } elseif ($model instanceof CompanyArea || $model instanceof \Apithy\CompanyPosition) {
            return ($model->company($this->company()->first()->id));
        } else {
            return false;
        }
    }

    /**
     * Return the enrollment status.
     *
     * @return string
     */
    public function getEnrollmentStatusTextAttribute()
    {
        $text = "indefinido";

        if ($this->pivot) {
            switch ($this->pivot->status) {
                case 1:
                    $text = "activo";
                    break;
                case 2:
                    $text = "completado";
                    break;
                case 5:
                    $text = "expulsado";
                    break;
            }

            return $text;
        } else {
            return "";
        }
    }

    /**
     * Return the enrollment status.
     *
     * @return string
     */
    public function getEnrollmentStatusColorAttribute()
    {
        $text = "gray";

        if ($this->pivot) {
            switch ($this->pivot->status) {
                case self::USER_ENROLLMENT_STATUS_ENROLLED:
                    $text = "blue";
                    break;
                case self::USER_ENROLLMENT_STATUS_EXPULSED:
                case self::USER_ENROLLMENT_STATUS_ABANDONED:
                    $text = "red";
                    break;
                case self::USER_ENROLLMENT_STATUS_EXPIRED:
                case self::USER_ENROLLMENT_STATUS_SUSPENDED:
                    $text = "red";
                    break;
                case self::USER_ENROLLMENT_STATUS_FINISHED:
                    $text = "orange";
                    break;
            }
            return $text;
        } else {
            return "";
        }
    }


    public function setBirthdayAttribute($date)
    {
        $this->attributes['birthday'] = date("Y-m-d 00:00:00", strtotime($date));
    }

    public function getBirthdayAttribute($value)
    {
        return Carbon::parse($value)->toFormattedDateString('d-m-Y');
    }


    public function getExperienceProgress()
    {
        return $this->belongsToMany(
            EnrollmentProgress::class,
            'experience_enrollment',
            'user_id',
            'id',
            'id',
            'enrollment_id'
        );
    }

    public function assignations()
    {
        return $this->belongsToMany(
            ExperienceAssignation::class,
            'experience_assignation_users',
            'user_id', //exp_assig_users.user_id
            'assignation_id', //exp_assig_users.assignation_id
            'id', //users.id
            'id' //experience_assignations.id
        )
            ->withTimestamps();
    }

    public function assignatedExperiences()
    {
        return $this->belongsToMany(
            Experience::class,
            'experiences_licences',
            'user_id',
            'experience_id',
            'id',
            'id'
        )
            ->withTimestamps();
    }

    public function getDefaulPaymentSource()
    {
        $paymentInformation = $this->paymentInformation()
            ->where("default_source", 1)
            ->first();

        if (!$paymentInformation) {
            $paymentInformation = $this->paymentInformation()
                ->latest()
                ->first();
        }

        return $paymentInformation;
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function licences()
    {
        return $this->hasMany(ExperienceLicence::class);
    }

    public function passwordRenew()
    {
        return $this->hasOne(PasswordRenew::class);
    }

    public function hasLicenceFor(Experience $experience)
    {
        return $this->hasOne(ExperienceLicence::class)
            ->where('experience_id', $experience->id)->first();
    }

    public function purchase($item_id, $type = 'experience')
    {
        return $this->hasOne(Purchase::class)
            ->where('item_id', $item_id)
            ->where('item_type', $type);
    }

    public function experiencesBuyed($user_for = 'personal_use')
    {
        $purchases = $this->purchases()
            ->where('item_type', 'experience')
            ->where($user_for, 1)
            ->orderBy('created_at', 'DESC')
            ->get();

        $purchase_ids = $purchases->pluck('item_id');

        return $purchase_ids;
    }


    public function experiencesNotStarted()
    {
        $purchases = $this->purchases()->where('item_type', 'experience')->get();
        $enrollments = $this->enrollments()->get();

        $pendings = $purchases->diff($enrollments)->pluck('id');

        return $pendings;
    }

    public function getCompanySystemAttribute()
    {
        $value = Cache::remember('company_system_user_' . $this->id, 360, function () {
            if ($this->company()->first()) {
                return $this->company()->first()->system_id;
            } else {
                return null;
            }
        });

        return $value;
    }

    public function getCompanyIdAttribute()
    {
        $value = Cache::remember('company_id_user_' . $this->id, 360, function () {
            if ($this->company()->first()) {
                return $this->company()->first()->id;
            } else {
                return null;
            }
        });

        return $value;
    }

    public function routeNotificationForTwilio()
    {
        if ($this->contact_preference == "whatsapp") {
            return 'whatsapp:+521' . $this->phone;
        } else {
            return '+521' . $this->phone;
        }
    }

    public function routeNotificationForSns()
    {
        if ($this->contact_preference == "whatsapp") {
            return 'whatsapp:+521' . $this->phone;
        } else {
            return '+52' . $this->phone;
        }
    }

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        //return $this->telegram_user_id;
        return 880361436;
    }

    /**
     * Send the confirmation code to a user.
     *
     * @param $user
     */
    public function sendConfirmationMessage()
    {
        if ($this->contact_preference == "mail") {
            $this->confirmation_code = Str::random(25);
            $this->save();
        } elseif ($this->contact_preference == "sms" || $this->contact_preference == "whatsapp") {
            $sms_service = new SmsService();
            $data = ['status' => 'pending', 'user_id' => $this->id, 'contact_number' => $this->phone];
            $sms_service->createSmsVerification($data);
        } else {
            $this->confirmation_code = Str::random(25);
            $this->save();
        }

        $notification = app(config('confirmation.notification'));
        $this->notify($notification);

        dispatch(new $notification($this))
            ->delay(Carbon::now()->addDays(1)->setHour(8)->setMinutes(30)->setSeconds(0));

        dispatch(new $notification($this))
            ->delay(Carbon::now()->addDays(2)->setHour(8)->setMinutes(30)->setSeconds(0));

        dispatch(new $notification($this))
            ->delay(Carbon::now()->addDays(3)->setHour(8)->setMinutes(30)->setSeconds(0));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPassword($token));
    }

    public function experiencesRatings()
    {
        return $this->belongsToMany(
            Experience::class,
            'experiences_rating',
            'user_id',
            'experience_id',
            'id',
            'id'
        )
            ->wherePivot('category', 'like', 'satisfaction')
            ->withPivot(['category', 'rating']);
    }

    public function experiencesCompleted()
    {
        return $this->hasMany(
            Enrollment::class,
            'user_id',
            'id'
        );
    }

    public function sessionsCompleted()
    {
        return $this->hasManyThrough(
            EnrollmentProgress::class,
            Enrollment::class,
            'user_id',
            'enrollment_id',
            'id',
            'id'
        )
            ->where('experience_enrollment_progress.status', EnrollmentService::SESSION_STATUS_FINISHED);
    }

    public function userGettingStartedItem()
    {
        return $this->belongsToMany(GettingStartedItem::class, 'user_getting_started_item')
            ->withPivot('status');
    }

    public function getEnvCompanyURL()
    {
        $env = env('APP_ENV', 'prod');
        $https = env('APP_HTTPS', false);

        if ($env != 'prod') {
            $env = $env . '.';
        } else {
            $env = 'www';
        }

        $protocol_part = 'http://';
        $company_part='';

        if ($https) {
            $protocol_part = 'https://';
        }

        if ($this->company()->first()) {
            $company_part=$this->company[0]->account_name.".";
        } else {
            return getEnvURL();
        }

        $url=$protocol_part .$company_part. $env . 'apithy.com';

        return $url;
    }

    // RELATIONS

    // SETTERS

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = Master::trimmed($name, true);
    }

    public function setSurnameAttribute($surname)
    {
        $this->attributes['surname'] = Master::trimmed($surname, true);
    }

    // SCOPES

    public function hasExperiences()
    {
        $user_id = $this->id;
        $user = User::findOrFail($user_id);

        $assignations = $user->licences()->where('buyed_by', '<>', $user->id)->get();
        $buyed = $user->experiencesBuyed('personal_use');

        $experience_ids = [];


        foreach ($assignations as $experience) {
            $experience_ids[] = $experience->experience_id;
        }

        foreach ($buyed as $experience) {
            $experience_ids[] = $experience;
        }

        if (count($experience_ids)) {
            return true;
        }


        return false;
    }
}
