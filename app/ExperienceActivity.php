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
 * Class Invitation
 * @package Apithy
 */
class ExperienceActivity extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, CacheQueryBuilder;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experience_activities';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'title' => 'string',
        'description' => 'string',
        'status' => 'integer',
        'resolution_time' => 'integer',
        'correct_response_pattern' => 'string',
        'type' => 'string',
        'url' => 'string',
        'is_iframe' => 'integer',
        'evaluation_id' => 'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'title',
        'description',
        'status',
        'resolution_time',
        'correct_response_pattern',
        'type',
        'url',
        'is_iframe',
        'is_scoreable',
        'library_machine_name',
        'library',
        'evaluation_id'
    ];

    protected $sortable = [
        'id',
        'title',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that can be filtered.
     *
     * @var array
     */
    protected $filterable = [
        'id',
        'title',
        'created_at',
        'updated_at',
    ];

    protected $appends = [];

    protected $scoreable_activities = [
        "H5P.ImageSequencing",
        "H5P.Column",
        "H5P.Blanks",
        "H5P.DragQuestion",
        "H5P.Flashcards",
        "H5P.MultiChoice",
        "H5P.QuestionSet",
        "apithy.SendText"
    ];

    /**
     * @inheritdoc
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('name', 'like', '%' . $term . '%')
                ->orWhere('title', 'like', '%' . $term . '%')
                ->orWhere('description', 'like', '%' . $term . '%');
        });
    }

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'experience_sessions_activities', 'activity_id');
    }

    public function enrollmentProgress()
    {
        return $this->belongsToMany(Session::class, 'experience_sessions_activities');
    }

    public function enrollmentTracking()
    {
        return $this->hasMany(EnrollmentTracking::class);
    }

    public function isScoreablebyLibraryName($library)
    {
        if (preg_grep("/^$library/", $this->scoreable_activities)) {
            return true;
        }

        return false;
    }
}
