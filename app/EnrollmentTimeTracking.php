<?php

namespace Apithy;

use Apithy\Services\EnrollmentService;
use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Apithy\Support\Database\CacheQueryBuilder;
use Illuminate\Notifications\Notifiable;

/**
 * Class EnrollmentTimeTracking
 * @package Apithy
 */
class EnrollmentTimeTracking extends Model implements Sortable, Filterable
{
    use SortTrait, FilterTrait,CacheQueryBuilder,Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experience_enrollment_time_tracking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_time',
        'enrollment_id',
        'enrollment_progress_id',
        'time',
        'json_data'
    ];

    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = true;
}
