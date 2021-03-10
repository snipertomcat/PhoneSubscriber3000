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
class Rating extends Model
{
    use CacheQueryBuilder;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experiences_rating';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'experience_id' => 'integer',
        'user_id' => 'integer',
        'rating' => 'integer',
        'category' => 'string',
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
        'rating',
        'category'
    ];

    protected $sortable = [
        'rating' => 'date'
    ];

    /**
     * The attributes that can be filtered.
     *
     * @var array
     */
    protected $filterable = [
        'id' => 'integer',
        'experience_id' => 'experience_id',
        'user_id' => 'integer',
        'rating' => 'date',
        'category' => 'string',
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
        return $this->belongsToMany(User::class);
    }
}
