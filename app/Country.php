<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 * @package Apithy
 */
class Country extends Model implements Sortable, Filterable
{
    use SortTrait, FilterTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'capital' => 'string',
        'citizenship' => 'string',
        'country_code' => 'string',
        'currency_sub_unit' => 'string',
        'currency_symbol' => 'string',
        'full_name' => 'string',
        'iso_3166_2' => 'string',
        'iso_3166_3' => 'string',
        'name' => 'string',
        'region_code' => 'string',
        'sub_region_code' => 'string',
        'eea' => 'boolean',
        'calling_code' => 'string',
        'flag' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * A country has many companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
