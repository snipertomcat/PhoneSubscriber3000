<?php

namespace Apithy;

use Apithy\Services\EnrollmentService;
use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Apithy\Support\Database\CacheQueryBuilder;
use Illuminate\Support\Facades\Storage;

/**
 * Class Country
 * @package Apithy
 */
class SessionsFiles extends Model implements Sortable, Filterable
{
    use SortTrait, FilterTrait, CacheQueryBuilder;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sessions_files';

    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'uuid',
        'url',
        'filename',
        'session_id',
        'status',
        'filemime'
    ];

    protected $appends = [
        'full_path_url',
    ];

    const STATUS_AVAILABLE = 1;


    public function session()
    {
        return $this->belongsTo(
            Session::class,
            'session_id',
            'id'
        );
    }

    public function hasUrl()
    {
        return !empty($this->attributes['url']);
    }

    /**
     * Get url for avatar image, if there is none the gravatar version
     * is returned.
     *
     * @return string
     */
    public function fileUrl()
    {
        if ($this->hasUrl()) {
            return Storage::url($this->attributes['url']);
        }

        $hash = md5(strtolower(trim($this->id)));

        //return "https://picsum.photos/640/480";
        return sprintf('https://www.gravatar.com/avatar/%s?d=identicon&size=512', $hash);
    }

    /**
     * Return full path cover.
     *
     * @return string
     */
    public function getFullPathUrlAttribute()
    {
        return $this->FileUrl();
    }
}
