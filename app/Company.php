<?php

namespace Apithy;

use Apithy\Utilities\Master\Master;
use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\HashIdTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Apithy\Support\Database\CacheQueryBuilder;

/**
 * Class User
 * @package Apithy
 */
class Company extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, HashIdTrait, CacheQueryBuilder;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'country_id' => 'integer',
        'name' => 'string',
        'account_name' => 'string',
        'site_url' => 'string',
        'contact_email' => 'string',
        'contact_phone' => 'string',
        'status' => 'integer',
        'logo' => 'string',
        'valid_domains' => 'array',
        'sector' => 'string',
        'legal_email' => 'string',
        'company_login_cover' => 'string'
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
        'name',
        'short_name',
        'legal_name',
        'country_id',
        'account_name',
        'site_url',
        'contact_email',
        'contact_phone',
        'logo',
        'status',
        'company_province',
        'rfc',
        'zip',
        'num_employees_min',
        'num_employees_max',
        'city',
        'valid_domains',
        'sector',
        'legal_address',
        'legal_email',
        'company_login_cover'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'full_path_logo',
        'system_id',
        'total_users',
        'full_path_cover'
    ];

    /**
     * The attributes that can be sorted.
     *
     * @var array
     */
    protected $sortable = [
        'id',
        'country_id',
        'name',
        'status',
        'created_at',
        'updated_at',
        'sector',
    ];

    /**
     * The attributes that can be filtered.
     *
     * @var array
     */
    protected $filterable = [
        'id',
        'country_id',
        'name',
        'account_name',
        'site_url',
        'contact_email',
        'contact_phone',
        'logo',
        'status',
        'created_at',
        'updated_at',
        'sector',
    ];

    /**
     * The methods that can be filtered.
     *
     * @var array
     */
    protected $filterableCustom = [];

    /**
     * Rules for Company cover
     *
     * @var array
     */
    public static $companyCoverRules = [
        'cover' => [
            'required',
            'image',
            'max:5242.88',
            'mimes:jpeg,png',
            'dimensions:min_width=1500,min_height=1080'
        ]
    ];

    /**
     * @inheritdoc
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('name', 'like', '%' . $term . '%')
                ->orWhere('account_name', 'like', '%' . $term . '%')
                ->orWhere('contact_phone', 'like', '%' . $term . '%');
        });
    }

    public function scopeAllowed($query)
    {
        $auth = Auth::user();
        if (!$auth->isSuper()) {
            $companyId = $auth->company[0]->id;
            $query->where('id', '=', $companyId);
        }

        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
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
     * A company has one country.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    /**
     * The companies that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user');
    }

    /**
     * A Company has many settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function settings($module = null, $setting = null, $value = null)
    {
        $relation=$this->hasMany(Setting::class, 'company_id', 'id');

        if ($module) {
            $relation->where('module', $module);
        }

        if ($setting) {
            $relation->where('setting', $setting);
        }

        if ($value) {
            $relation->where('value', $value);
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
     * Return full path avatar.
     *
     * @return string
     */
    public function getFullPathLogoAttribute()
    {
        return $this->logoUrl();
    }

    /**
     * Verifies if an avatar is present.
     *
     * @return bool
     */
    public function hasLogo()
    {
        return !empty($this->attributes['logo']);
    }


    /**
     * Get url for avatar image, if there is none the gravatar version
     * is returned.
     *
     * @return string
     */
    public function logoUrl()
    {
        if ($this->hasLogo()) {
            return Storage::url($this->attributes['logo'])."?time=".time();
        }

        $hash = md5(strtolower(trim($this->attributes['name'])));

        return sprintf('https://www.gravatar.com/avatar/%s?d=identicon&size=512', $hash);
    }

    /**
     * Verifies if an cover is present.
     *
     * @return bool
     */
    public function hasCover()
    {
        return isset($this->attributes['company_login_cover']);
    }

    /**
     * Get url for cover image, if there is none the default image version is returned
     *
     * @return string
     */
    public function getFullPathCoverAttribute()
    {
        if ($this->hasCover()) {
            return Storage::url($this->attributes['company_login_cover'])."?time=".time();
        }
        return asset('/images/imagen-default-login-empresa.png');
    }

    /**
     * Check is the company has users associated.
     *
     * @return bool
     */
    public function hasUsers()
    {
        return !empty($this->users()->first());
    }

    /**
     * Get all areas of a company.
     *
     * @return mixed
     */
    public function areas()
    {
        return $this->hasMany(CompanyArea::class);
    }

    /**
     * Get all abilities of a company.
     *
     * @return mixed
     */
    public function abilities()
    {
        return $this->hasMany(Ability::class);
    }

    /**
     * Get all areas of a company.
     *
     * @return mixed
     */

    public function experiences()
    {
        return $this->belongsToMany(
            Experience::class,
            'experience_company'
        );
        //return $this->hasMany(Experience::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function positions()
    {
        // Solo regresa las posiciones de la primera área
        return $this->hasManyThrough(
            CompanyPosition::class,
            CompanyArea::class,
            'company_id',
            'area_id',
            'id',
            'id'
        );
    }

    /**
     * @return mixed
     */
    public function areasPositions()
    {
        return $this->areas()->with(['positions']);
    }

    public static function getDefautCompanyId()
    {
        return Company::where('name', 'Apithy')->first()->id;
    }

    public function tags()
    {
        return $this->hasMany(
            Tag::class,
            'company_id',
            'id'
        );
    }

    public function taxonomy()
    {
        return $this->hasMany(Taxonomy::class);
    }

    public function taxonomyAreas()
    {
        return $this->hasMany(Taxonomy::class)
            ->where('type', '=', 'company_area');
    }

    public function taxonomyPositions()
    {
        return $this->hasMany(Taxonomy::class)
            ->where('type', '=', 'company_position');
    }

    public function budgets()
    {
        return $this->hasMany(
            CompanyBudget::class
        );
    }

    public function budget()
    {
        return $this->budgets()->latest()->first();
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'company_id', 'id');
    }

    public function getTotalUsersAttribute()
    {
        return count($this->users()->get());
    }

    // FUNCTIONS

    public static function getDomainLogo(Request $request)
    {
        $logo = asset('images/mails/1391545686622704.png');
        $user = $request->user();
        if (!isset($user)) {
            return $logo;
        }
        $company = $user->company()->first();
        if ($company->hasLogo()) {
            return $company->full_path_logo;
        }
        return $logo;
    }

    // SETTERS

    public function setAccountNameAttribute($account_name)
    {
        $this->attributes['account_name'] = Master::trimmed($account_name, false);
    }

    public function setLegalNameAttribute($legal_name)
    {
        $this->attributes['legal_name'] = Master::trimmed($legal_name, false);
    }

    public function setShortNameAttribute($short_name)
    {
        $this->attributes['short_name'] = Master::trimmed($short_name, false);
    }

    public function setLegalAddressAttribute($legal_address)
    {
        $this->attributes['legal_address'] = Master::trimmed($legal_address, true);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = Master::trimmed($name, false);
    }

    // GETTERS

    public function getAccountNameAttribute()
    {
        if ($this->attributes['account_name'] === 'apithy') {
            return 'www';
        }
        return $this->attributes['account_name'];
    }

    /**
     * A experience has a certificates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function certificates()
    {
        return $this->hasMany(Certificates::class, "company_id", 'id');
    }
}
