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
class WebsiteContact extends Model implements Sortable, Filterable
{
    use SortTrait, FilterTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'website_contacts';

    protected $guarded = array();


    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = true;
}
