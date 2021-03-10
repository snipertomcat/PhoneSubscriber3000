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
class ExperienceDraft extends Model
{
    use SortTrait, CacheQueryBuilder;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experiences_content_draft';


    /**
     * Posible Statuses for a Expecience.
     *
     * @var string
     */

    const STATUS_INACTIVE = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT = 2;

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
        'status' => 'integer',
        'creator' => 'integer'
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
        "type",
        'title',
        'summary',
        'description',
        'status',
        'creator',
        'json_data',
        'experience_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
