<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class experienceExecution
 * @package Apithy
 */
class ExperienceAssignation extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experience_assignations';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'experience_id' => 'integer',
        'user_id' => 'integer',
        'description' => 'string',
        'type' => 'string',
        'user_data_json' => 'array',
        'company_visibility_settings' => 'array',
        'user_visibility_settings' => 'array',
        'status' => 'integer',
        'start' => 'date',
        'finish' => 'date'
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
        'id' ,
        'experience_id',
        'user_id' ,
        'description',
        'type' ,
        'user_data_json',
        'company_visibility_settings',
        'user_visibility_settings' ,
        'status',
        'start' ,
        'finish',
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

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    /**
     * The experience execution belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */

    public function users()
    {
        return $this->hasMany(User::class, 'experience_execution_user');
    }
}
