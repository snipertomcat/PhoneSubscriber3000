<?php

namespace Apithy;

use Apithy\Services\EnrollmentService;
use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\HashIdTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Apithy\Support\Database\CacheQueryBuilder;
use Stripe\Product as StripeProduct;
use Stripe\SKU as StripeSku;
use Stripe\Stripe;

/**
 * Class Experience
 * @package Apithy
 */
class Experience extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, HashIdTrait, CacheQueryBuilder;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experiences';


    /**
     * Posible Statuses for a Expecience.
     *
     * @var string
     */

    const STATUS_INACTIVE = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT = 2;

    const VISIBILITY_PRIVATE = 0;
    const VISIBILITY_PUBLIC = 1;

    /*Webmaster ID*/
    const AUTHOR_DEFAULT = 1;

    const TYPE_ADVENTURE = 'adventure';
    const TYPE_JOURNEY = 'journey';

    const TYPE_PRIMARY = 'primary';
    const TYPE_INHERIT = 'inherit';


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'summary' => 'string',
        'description' => 'string',
        'code' => 'string',
        'company_objectives' => 'string',
        'areas_objectives' => 'string',
        'points_default' => 'integer',
        'duration_limit__default' => 'integer',
        'level_default' => 'integer',
        'price_default' => 'double',
        'visibility' => 'integer',
        'status' => 'integer',
        'available_from' => 'date',
        'available_to' => 'date',
        'author' => 'integer',
        'partner' => 'integer',
        'video_teaser' => 'string',
        'cover' => 'string',
        'cover_top' => 'string',
        'predecessor_experience' => 'integer',
        'successor_experience' => 'integer',
        'user_visibility_settings' => "array",
        'company_visibility_settings' => "array",
        'instructors' => "array",
        'features' => "array",
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'available_from',
        'available_to',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "type",
        'title',
        'summary',
        'description',
        'code',
        'company_objectives',
        'area_objectives',
        'points_default',
        'duration_limit_default',
        'level_default',
        'price_default',
        'visibility',
        'status',
        'available_from',
        'available_to',
        'author',
        'partner',
        'cover',
        'user_visibility_settings',
        'company_visibility_settings',
        'company_id',
        'instructors',
        'features',
        'cover_top',
        'video_teaser'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'full_path_cover',
        'system_id',
        'current_company_enrollments',
        'price',
        'company_author',
        'current_user_progress',
        //'users_rating',
        'admin_available_licences',
        'is_auth_user_purchased',
        'auth_user_has_licence',
        'is_free',
        'full_path_cover_top',
        'url',
        'min_score',
        'min_evaluation_score'
    ];

    /**
     * The attributes that can be sorted.
     *
     * @var array
     */
    protected $sortable = [
        'id',
        'title',
        'code',
        'status',
        'available_from',
        'available_to',
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
        'title',
        'summary',
        'description',
        'company_objectives',
        'areas_objectives',
        'points_default',
        'available_from',
        'available_to',
        'author',
        'partner',
        'status',
        'created_at',
        'updated_at',
        'abilities'
    ];

    /**
     * The methods that can be filtered.
     *
     * @var array
     */
    protected $filterableCustom = [];


    protected $company_settings_atributes = [
        'id',
        'experience_id',
        'company_id',
        'instructor',
        'company_objectives',
        'area_objectives',
        'available_from',
        'available_to',
        'start',
        'finish',
        'points',
        'level',
        'price',
        'duration_limit',
        'visibility',
        'user_visibility_settings',
        'company_visibility_settings',
        'status',
        'created_at',
        'updated_at',
        'cover',
    ];


    /**
     * @inheritdoc
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('title', 'like', '%' . $term . '%')
                ->orWhere('description', 'like', '%' . $term . '%')
                ->orWhere('company_objectives', 'like', '%' . $term . '%')
                ->orWhere('area_objectives', 'like', '%' . $term . '%');
        });
    }

    /**
     * @inheritdoc
     */
    public function scopeFilter($query, array $terms)
    {
        /*
         *   Array ( [type] => 184 [abilities] => 50 )
         *
         * */

        if (isset($terms['abilities'])) {
            $query->whereHas('abilities', function ($abilitity) use ($terms) {
                $abilitity->whereIn('id', $terms)->allowed();
            });
        }

        if (isset($terms['available_to'])) {
            $query->where('available_to', '>=', $terms['available_to'])
                ->allowed();
        }

        if (isset($terms['type'])) {
            $query->where('type', $terms['type'])
                ->allowed();
        }

        if (isset($terms['author'])) {
            $query->where('author', $terms['author'])
                ->allowed();
        }
    }

    public function scopeSystemVisible($query)
    {
        $auth = Auth::user();
        if ($auth->isSuper() || $auth->isAdmin()) {
            $query->where("status", "<>", self::STATUS_DRAFT);
        } else {
            $query->where("status", "<>", self::STATUS_DRAFT);
            $query->where("status", "<>", self::STATUS_INACTIVE);
        }


        return $query;
    }

    public function scopeStatus($query, $requestedstatus = self::STATUS_PUBLISHED)
    {
        switch ($requestedstatus) {
            case self::STATUS_PUBLISHED:
                $status = self::STATUS_PUBLISHED;
                break;
            case self::STATUS_INACTIVE:
                $status = self::STATUS_INACTIVE;
                break;
            case self::STATUS_DRAFT:
                $status = self::STATUS_DRAFT;
                break;
        }

        return $query->where('status', $status);
    }

    public function scopeAllowed($query)
    {

        $auth = Auth::user();
        //Only root Admin can show all the experiences
        if (!$auth->isSuper()) {
            $query->where('visibility', 1);
            $companyId = $auth->company[0]->id;
            $query->OrWhereHas('companies', function ($company) use ($companyId) {
                $company->where('companies.id', $companyId);
            });
        }

        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
    }

    public function scopeStoreOnly($query)
    {

        $auth = Auth::user();
        //Only root Admin can show all the experiences
        if (!$auth->isSuper()) {
            $query->where('visibility', 1);
            $companyId = $auth->company[0]->id;
            $query->whereDoesntHave('companies', function ($company) use ($companyId) {
                $company->where('companies.id', $companyId);
            });
        }

        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
    }

    public function scopeCompanyOnly($query)
    {
        $auth = Auth::user();
        //Only root Admin can show all the experiences
        $companyId = $auth->company[0]->id;

        if ($companyId !== Company::getDefautCompanyId()) {
            $query->whereHas('companies', function ($company) use ($companyId) {
                $company->where('companies.id', $companyId);
            });
        }

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
     * The experience can has many Adventures.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function adventures()
    {
        return $this->belongsToMany(Experience::class, 'journeys', 'journey_id')
            ->withPivot(['weight', 'parent_id', 'experience_id']);
    }


    /**
     * The experience can has many Journeys.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function journeys()
    {
        return $this->belongsTo(Experience::class, 'journeys', 'journey_id');
    }


    /**
     * The experience that belong to a company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'experience_company');
    }

    /**
     * The experience that belong to a enrollment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function enrollments($useCompanyFromUser = true, $company = true, $type = 'primary')
    {
        $relation = $this->belongsToMany(User::class, 'experience_enrollment')
            ->where('experience_enrollment.type', $type)
            ->withPivot(
                'status',
                'experience_assignation_id',
                'id',
                'created_at',
                'updated_at',
                'score',
                'started_at',
                'finished_at'
            );

        if ($useCompanyFromUser) {
            if (Auth::check()) {
                $auth = Auth::user();
                if (count($auth->company)) {
                    $company = $auth->company[0]->id;
                }
            } else {
                $company = false;
            }
        }

        if ($company) {
            if (is_numeric($company)) {
                $companyId = $company;
            } else {
                $companyId = $company->id;
            }

            $relation->whereHas('companies', function ($company) use ($companyId) {
                $company->where('companies.id', $companyId);
            });
        }

        return $relation;
    }

    /**
     * A experience has a author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function author()
    {
        return $this->hasOne(User::class, "id", 'author');
    }

    /**
     * An author has a company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function getCompanyAuthorAttribute()
    {
        $user = $this->author()->with("companies")->first();
        if ($user) {
            return $user->company[0];
        }

        return false;
    }

    /**
     * A experience has a Partner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function partner()
    {
        return $this->hasOne(User::class, "id", 'partner');
    }

    /**
     * A experience has many sessions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function sessions()
    {
        return $this->hasMany(Session::class)
            ->whereNotIn('status', [Session::STATUS_DRAFT, Session::STATUS_DELETED])
            ->where('parent_id', null)
            ->orderBy('weight');
    }

    /**
     * A experience has many sessions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function allSessions()
    {
        return $this->hasMany(Session::class)
            ->where('status', "<>", 2)
            ->orderBy('weight');
    }

    /**
     * A experience has many sessions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function allAvailableSessions()
    {
        return $this->hasMany(Session::class)
            ->where('status', "<>", 2)
            ->where('status', "<>", 3)
            ->orderBy('weight');
    }

    /**
     * A experience has many executions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function assignations()
    {
        return $this->hasMany(ExperienceAssignation::class, 'experience_id', 'id');
    }

    /**
     * A experience has many abilities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'experience_ability');
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
     * Check is the company has users associated.
     *
     * @return bool
     */
    public function hasUsers()
    {
        return !empty($this->users()->first());
    }

    /**
     * Verifies if an cover is present.
     *
     * @return bool
     */
    public function hasCover()
    {
        return !empty($this->attributes['cover']);
    }


    /**
     * Verifies if an cover is present.
     *
     * @return bool
     */
    public function hasCoverTop()
    {
        return !empty($this->attributes['cover_top']);
    }

    /**
     * Return full path cover.
     *
     * @return string
     */
    public function getFullPathCoverAttribute()
    {
        return $this->coverUrl();
    }

    /**
     * Return full path cover.
     *
     * @return string
     */
    public function getFullPathCoverTopAttribute()
    {
        return $this->coverTopUrl();
    }

    /**
     * Get url for avatar image, if there is none the gravatar version
     * is returned.
     *
     * @return string
     */
    public function coverUrl()
    {
        if ($this->hasCover()) {
            return Storage::url($this->attributes['cover']);
        }

        $hash = md5(strtolower(trim($this->id)));

        //return "https://picsum.photos/640/480";
        return sprintf('https://www.gravatar.com/avatar/%s?d=identicon&size=512', $hash);
    }

    /**
     * Get url for avatar image, if there is none the gravatar version
     * is returned.
     *
     * @return string
     */
    public function coverTopUrl()
    {
        if ($this->hasCoverTop()) {
            return Storage::url($this->attributes['cover_top']);
        }

        $hash = md5(strtolower(trim($this->id)));

        //return "https://picsum.photos/640/480";
        return sprintf('https://www.gravatar.com/avatar/%s?d=identicon&size=512', $hash);
    }


    /*
     *
     *Get the enrollments in the current companies user logged
     *
     * @return integer
     */

    public function getCurrentCompanyEnrollmentsAttribute()
    {
        return $this->enrollments(true)->get()->count();
    }

    /**
     * Return getCompanyObjectives.
     *
     * @return string
     */
    public function getCompanyObjectivesAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->company_objectives) {
                return $settings->company_objectives;
            }
        }


        return $this->attributes['company_objectives'];
    }

    /**
     * Return getCompanyObjectives.
     *
     * @return string
     */
    public function getAreaObjectivesAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->area_objectives) {
                return $settings->area_objectives;
            }
        }

        return $this->attributes['area_objectives'];
    }

    /**
     * Return CompanyVisibilitySettings.
     *
     * @return string
     */
    public function getCompanyVisibilitySettingsAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->company_visibility_settings) {
                return json_decode($settings->company_visibility_settings, true);
            }
        }

        return $this->attributes['company_visibility_settings'];
    }

    /**
     * Return CompanyVisibilitySettings.
     *
     * @return string
     */
    public function getUserVisibilitySettingsAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->user_visibility_settings) {
                return json_decode($settings->user_visibility_settings, true);
            }
        }

        return $this->attributes['user_visibility_settings'];
    }

    /**
     * Return getAvailableFrom.
     *
     * @return string
     */
    public function getAvailableFromAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->available_from) {
                return $settings->available_from;
            }
        }

        return $this->attributes['available_from'];
    }

    /**
     * Return getAvailableTo.
     *
     * @return string
     */
    public function getAvailableToAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->available_to) {
                return $settings->available_to;
            }
        }

        return $this->attributes['available_to'];
    }

    /**
     * Return getAvailableTo.
     *
     * @return string
     */
    public function getVisibilityAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->visibility) {
                return $settings->visibility;
            }
        }

        return $this->attributes['visibility'];
    }

    /**
     * Return getAvailableTo.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->status) {
                return $settings->status;
            }
        }

        return $this->attributes['status'];
    }

    /**
     * Return getAvailableTo.
     *
     * @return string
     */
    public function getCoverAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->cover) {
                return $settings->cover;
            }
        }

        return $this->coverUrl();
    }


    /**
     * Return getPrice.
     *
     * @return string
     */
    public function getPriceAttribute()
    {
        $settings = $this->getCompanySettingsData();

        if ($settings) {
            if ($settings->price) {
                return $settings->price;
            }
        }

        return $this->attributes['price_default'];
    }

    /**
     * Return full path cover.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('experience.preview', $this->system_id);
    }
}
