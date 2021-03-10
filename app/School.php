<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class School
 * @package Apithy
 */
class School extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schools';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'remote_id' => 'integer',
        'country_id' => 'integer',
        'language_id' => 'integer',
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
        'remote_id',
        'code',
        'name',
        'country_id',
        'language_id',
        'timezone',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that can be filtered.
     *
     * @var array
     */
    protected $filterable = [
        'id',
        'remote_id',
        'code',
        'name',
        'country_id',
        'language_id',
        'timezone',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that can be sortable.
     *
     * @var array
     */
    protected $sortable = [
        'id',
        'remote_id',
        'code',
        'name',
        'country_id',
        'language_id',
        'timezone',
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
                ->orWhere('name', 'like', '%'.$term.'%')
                ->orWhere('code', 'like', '%'.$term.'%')
                ->orWhere('timezone', 'like', '%'.$term.'%');
        });
    }

    /**
     * The users that belong to the school.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * A school has one country.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * A school has one language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
