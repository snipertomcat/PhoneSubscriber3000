<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Apithy\Support\Database\CacheQueryBuilder;
use Laravel\Cashier\Billable;

/**
 * Class Country
 * @package Apithy
 */
class Purchase extends Model implements Sortable, Filterable
{
    use SortTrait, FilterTrait, Billable, CacheQueryBuilder;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_purchases';


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'item_id' => 'integer',
        'item_type' => 'string',
        'assignation_id' => 'integer',
        'corporative_use' => 'integer',
        'personal_use' => 'integer',
        'company_areas' => 'array',
        'company_positions' => 'array',
        'company_users' => 'array',
        'new_users' => 'array',
    ];


    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */

    public $timestamps = false;


    protected $guarded = [];


    /**
     * Return the user of this PaymentInformation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function experience()
    {
        if ($this->item_type == "experience") {
            return $this->belongsTo(Experience::class);
        }
    }
}
