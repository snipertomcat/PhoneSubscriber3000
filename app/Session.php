<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\HashIdTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Apithy\Support\Database\CacheQueryBuilder;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class User
 * @package Apithy
 */
class Session extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, HashIdTrait, CacheQueryBuilder;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'experience_sessions';


    /**
     * Posible Statuses for a Expecience.
     *
     * @var string
     */

    const STATUS_INACTIVE = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT = 2;
    const STATUS_DELETED = 3;

    /*Webmaster ID*/
    const AUTHOR_DEFAULT = 1;

    const AVAILABLE_TYPES = [
        ["value" => "exploration"],
        ["value" => "practice"],
        ["value" => "exam"]
    ];


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
        'resource_url' => 'String',
        'code' => 'string',
        'weight' => 'integer',
        'visibility' => 'integer',
        'status' => 'integer',
        'available_from' => 'date',
        'available_to' => 'date',
        'author' => 'integer',
        'partner' => 'integer',
        'cover' => 'string',
        'successor_experience' => 'integer',
        'user_visibility_settings' => "array",
        'company_visibility_settings' => "array",
        'json_data' => "array",
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'available_from',
        'available_to',
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
        'code',
        'resource_url',
        'visibility',
        'status',
        'weight',
        'available_from',
        'available_to',
        'author',
        'partner',
        'cover',
        'user_visibility_settings',
        'company_visibility_settings',
        'company_id',
        'experience_id',
        'current_enrollment_progress',
        'json_data',
        'parent_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'full_path_cover',
        'system_id',
    ];
    /**
     * The attributes that can be sorted.
     *
     * @var array
     */
    protected $sortable = [
        'id',
        'title',
        'visibility',
        'status',
        'available_from',
        'available_to',
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
        'summary',
        'description',
        'available_from',
        'available_to',
        'author',
        'partner',
        'visibility',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * The methods that can be filtered.
     *
     * @var array
     */
    protected $filterableCustom = [];

    /**
     * @inheritdoc
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('title', 'like', '%' . $term . '%')
                ->orWhere('summary', 'like', '%' . $term . '%')
                ->orWhere('description', 'like', '%' . $term . '%');
        });
    }

    public function scopeSystemVisible($query)
    {
        $query->where("status", "<>", self::STATUS_DRAFT);
        return $query;
    }

    public function scopeAllowed($query)
    {
        $auth = Auth::user();
        if ($auth->can('fetch', Experience::class)) {
            if (!$auth->isSuper()) {
                $companyId = $auth->company[0]->id;
                $query->whereHas('author', function ($author) use ($companyId) {
                    $author->whereHas('company', function ($company) use ($companyId) {
                        $company->where('id', $companyId);
                    });
                });
            }
        }

        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
    }

    /**
     * Get all of the user's metadata.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metadata()
    {
        return $this->morphMany(Metadata::class, 'metadatable');
    }


    /**
     * The experience can own to a company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    /**
     * A experience has a author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function author()
    {
        return $this->hasOne(User::class, "id", 'author');
    }

    /**
     * A experience has a Partner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function partner()
    {
        return $this->hasOne(User::class, "id", 'partner');
    }

    /**
     * Modifies or creates a new metadata relationship with the given key and value.
     *
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function metadataFor($key, array $value)
    {
        $meta = $this->metadata()->firstOrNew(['key' => $key]);
        $meta->value = $value;
        $meta->save();
        return $this;
    }

    /**
     * Verifies if an cover is present.
     *
     * @return bool
     */
    public function hasCover()
    {
        return !empty($this->attributes['cover']);
    }

    /**
     * Return full path cover.
     *
     * @return string
     */
    public function getFullPathCoverAttribute()
    {
        return $this->coverUrl();
    }

    /**
     * Get url for avatar image, if there is none the gravatar version
     * is returned.
     *
     * @return string
     */
    public function coverUrl()
    {
        if ($this->hasCover()) {
            return Storage::url($this->attributes['cover']);
        }

        $hash = md5(strtolower(trim($this->id)));

        return sprintf('https://www.gravatar.com/avatar/%s?d=identicon&size=512', $hash);
    }

    /**
     * A session has many abilities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'abilities_session')->withTimestamps();
    }

    /**
     * A session has many tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tags_session')->withTimestamps();
    }

    public function taxonomy()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_sessions');
    }

    public function taxonomyTags()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_sessions')
            ->where('type', '=', 'tag');
    }

    public function taxonomyAbility()
    {
        return $this->belongsToMany(Taxonomy::class, 'taxonomy_sessions')
            ->where('type', '=', 'ability');
    }

    public function generateJsonData()
    {
        return [];

        $css_libraries = [
            'magnific-popup.css',
            'owl.carousel.css',
            'mdb.min.css',
            'fontawesome',
            'font-awesome',
            'bootstrap.min.css',
            'bootstrap.css'
        ];

        $js_libraries = [
            'jquery-3.3.1.min.js',
            'owl.carousel.js',
            'jquery.magnific-popup.min.js',
            'mdb.min.js',
            'h5p-resizer.js',
            'h5p-iframes.js'
        ];

        $content_json = [
            'type' => '',
            'header' => false,
            'main' => '',
            'references' => false,
            'js_scripts' => [],
            'css' => [],

        ];

        $base_url = str_replace('/index.html', '', $this->resource_url);

        if (preg_match('/h5p/', $this->resource_url)) {
            $content_json['type'] = 'h5p-iframe';
            $content_json['main'] = "
<iframe class='h5p-iframe' src='{$this->resource_url}' width='80%' 
style='width: 80% !important;margin: 0 auto; 
display: block' allowfullscreen='allowfullscreen' frameBorder='0'></iframe>";
        } else {
            $content_json['type'] = 'html';
            $client = new Client();
            $response = $client->get($this->resource_url);
            $content = $response->getBody()->getContents();

            $crawler = new Crawler($content);

            foreach ($crawler->filter('link') as $link) {
                if ($link->getAttribute('rel') == 'stylesheet') {
                    if (substr($link->getAttribute('href'), 0, 2) == '//') {
                        $content_json['css'][] = $link->getAttribute('href');
                    } else {
                        if (!preg_match('/' . implode('|', $css_libraries) . '/', $link->getAttribute('href'))) {
                            if (!filter_var(
                                $link->getAttribute('href'),
                                FILTER_VALIDATE_URL,
                                [FILTER_FLAG_HOSTNAME, FILTER_FLAG_SCHEME_REQUIRED]
                            )) {
                                $link->setAttribute('href', $base_url . '/' . $link->getAttribute('href'));
                            }

                            $content_json['css'][] = $link->getAttribute('href');
                        }
                    }
                }
            }

            /***Rewrite Iframes URL For H5p enviroments**/

            if (env('APP_ENV') == 'local') {
                foreach ($crawler->filter('iframe') as & $activity) {
                    if (preg_match(
                        '/evaluations.dev.apithy.com || evaluations.apithy.com/',
                        $activity->getAttribute('src')
                    )) {
                        $activity_url = str_replace(
                            '//evaluations.dev.apithy.com',
                            'http://evaluations.local.apithy.com',
                            $activity->getAttribute('src')
                        );
                        $activity_url = str_replace(
                            '//evaluations.apithy.com',
                            'http://evaluations.local.apithy.com',
                            $activity->getAttribute('src')
                        );
                        $activity->setAttribute('src', $activity_url);
                    }
                }
            }

            foreach ($crawler->filter('img') as &$image) {
                if (!preg_match('~' . $base_url . '~', $image->getAttribute('src'))) {
                    $image->setAttribute('src', $base_url . '/' . $image->getAttribute('src'));
                }
            }

            foreach ($crawler->filter('a') as &$image) {
                if ($image->getAttribute('class') == 'img-modal') {
                    $image->setAttribute('href', $base_url . '/' . $image->getAttribute('href'));
                }
            }

            foreach ($crawler->filter('video') as &$video) {
                if (!preg_match('~' . $base_url . '~', $video->getAttribute('poster'))) {
                    $video->setAttribute('poster', $base_url . '/' . $video->getAttribute('poster'));
                }
            }

            foreach ($crawler->filter('video > source') as &$video) {
                if (!preg_match('~' . $base_url . '~', $video->getAttribute('src'))) {
                    $video->setAttribute('src', $base_url . '/' . $video->getAttribute('src'));
                }
            }

            foreach ($crawler->filter('script') as $link) {
                if ($link->hasAttribute('src')) {
                    if (substr($link->getAttribute('src'), 0, 2) == '//') {
                        $content_json['js_scripts'][] = ['type' => 'src', 'value' => $link->getAttribute('src')];
                    } elseif (!preg_match('/' . implode('|', $js_libraries) . '/', $link->getAttribute('src'))) {
                        if (!filter_var($link->getAttribute('src'), FILTER_VALIDATE_URL)) {
                            $link->setAttribute('src', $base_url . '/' . $link->getAttribute('src'));
                        }
                        $content_json['js_scripts'][] = ['type' => 'src', 'value' => $link->getAttribute('src')];
                    }
                } else {
                    $script_content = "$(document).ready(function() {{$link->nodeValue}});";
                    $content_json['js_scripts'][] = ['type' => 'inline', 'value' => $script_content];
                }
            }

            if ($crawler->filter('section')->count()) {
                foreach ($crawler->filter('section') as $index => &$section) {
                    if ($section->hasAttribute('class')) {
                        if ($section->getAttribute('class') == 'apithy-information-sources') {
                            $content_json['references'] = $crawler->filter('section')->eq($index)->html();
                            $section->nodeValue = "";
                        }
                    }
                }
            }

            if ($crawler->filter('body > header')->count()) {
                $content_json['header'] = $crawler->filter('body > header')->eq(0)->html();
            }

            if ($crawler->filter('body > main')->count()) {
                $content_json['main'] = $crawler->filter('body > main')->eq(0)->html();
            }
        }


        $this->attributes['json_data'] = json_encode($content_json);
    }

    public function getActivities()
    {
        return $this->belongsToMany(ExperienceActivity::class, 'experience_sessions_activities');
    }

    public function activities()
    {
        return $this->belongsToMany(
            ExperienceActivity::class,
            'experience_sessions_activities',
            'session_id',
            'activity_id'
        );
    }

    /**
     * Load a previous session
     *
     * @return Session
     */

    public function prevSession()
    {
        return $this->hasOne(Session::class, 'experience_id', 'experience_id')
            ->where('status', '!=', Session::STATUS_DRAFT)
            ->where('weight', '<', $this->weight)->orderByDesc('weight');
    }

    /**
     * Load a next session
     *
     * @return Session
     */

    public function nextSession()
    {
        return $this->hasOne(Session::class, 'experience_id', 'experience_id')
            ->where('status', '!=', Session::STATUS_DRAFT)
            ->where('weight', '>', $this->weight)->orderBy('weight');
    }

    public function getSiblings()
    {
    }

    // RELATIONS

    public function evaluations()
    {
        return $this->belongsToMany(
            Evaluation::class,
            'evaluation_experience_session',
            'experience_session_id',
            'evaluation_id'
        )->where('evaluation_experience_session.status', '<>', Evaluation::EVALUATION_EXPERIENCE_STATUS_DELETED)
            ->withPivot('weight');
    }

    /**
     * Get all the childs of this session.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function childs()
    {
        return $this->hasMany(Session::class, 'parent_id', 'id')
        ->where('status', self::STATUS_PUBLISHED)
        ->orderBy('weight');
    }

    public function progress()
    {
        return $this->hasOne(EnrollmentProgress::class);
    }

    public function enrollmentProgress()
    {
        return $this->hasMany(EnrollmentProgress::class, 'session_id', 'id');
    }
}
