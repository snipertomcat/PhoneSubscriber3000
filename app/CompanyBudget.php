<?php

namespace Apithy;

use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\HashIdTrait;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Apithy\Support\Database\CacheQueryBuilder;

/**
 * Class CompanyArea
 * @package Apithy
 */
class CompanyBudget extends Model
{
    use SortTrait, FilterTrait, HashIdTrait,CacheQueryBuilder;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_budget';

    protected $guarded = [];

    const BUDGET_TYPE_IN = 'in';
    const BUDGET_TYPE_OUT = 'out';
    const BUDGET_GLOBAL = 'global';
    const BUDGET_ANNUAL = 'annual';
    const BUDGET_BIANNUAL = 'biannual';


    /**
     * Get the company of an area.
     *
     * @param $company_id
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
