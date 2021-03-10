<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Apithy\Support\Database\CacheQueryBuilder;

/**
 * Class experienceExecution
 * @package Apithy
 */
class ExperienceSettings extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, CacheQueryBuilder;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experiences_settings';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'experience_id' => 'experience_id',
        'instructor' => 'integer',
        'start' => 'date',
        'finish' => 'date',
        'points' => 'integer',
        'level' => 'integer',
        'price' => 'integer',
        'duration_limit' => 'integer',
        'company_objectives' => 'string',
        'area_objectives' => 'string',
        'available_from' => 'date',
        'available_to' => 'date',
        'visibility' => 'integer',
        'user_visibility_settings' => 'array',
        'company_visibility_settings' => 'array',
        'status' => 'status',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start',
        'finish',
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
    ];

    protected $sortable = [
        'experience_id',
        'start',
        'finish',
        'points',
        'level',
        'price',
        'duration_limit',

    ];

    /**
     * The attributes that can be filtered.
     *
     * @var array
     */
    protected $filterable = [
        'id',
        'experience_id',
        'instructor',
        'creator',
        'created_at',
        'updated_at',
    ];

    /**
     * @inheritdoc
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('name', 'like', '%' . $term . '%')
                ->orWhere('description', 'like', '%' . $term . '%');
        });
    }

    public function scopeAllowed($query)
    {
        $auth = Auth::user();
        if ($auth->can('fetch', self::class)) {
            if (!$auth->isSuper()) {
                $companyId = $auth->company[0]->id;

                $query->whereHas('company', function ($q) use ($companyId) {
                    $q->where('id', $companyId);
                });
            }
        }

        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
    }

    /**
     * The experience execution has one experience.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

    public function experiences()
    {
        return $this->hasOne(Experience::class);
    }

    /**
     * The experience execution belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */

    public function company()
    {
        return $this->belongsToOne(Company::class);
    }
}
