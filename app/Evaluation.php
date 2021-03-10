<?php

namespace Apithy;

use Apithy\Utilities\Master\Master;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{

    protected $casts = [
        'id'                => 'integer',
        'company_id'        => 'integer',
        'user_id'           => 'integer',
        'name'              => 'string',
        'title'             => 'string',
        'description'       => 'string',
        'type'              => 'string',
        'difficulty'        => 'string',
        'creation_type'     => 'string',
        'configurations'    => 'array'
    ];

    public $fillable = [
        'name',
        'title',
        'description',
        'type',
        'difficulty',
        'creation_type',
        'configurations'
    ];

    const TYPE = ['diagnostic', 'activity', 'finally'];
    const DIFFICULTY = ['basic', 'intermediate', 'advanced'];
    const CREATION_TYPE = ['dynamic', 'manual'];

    const EVALUATION_EXPERIENCE_STATUS_DELETED = 2;


    // RELATIONS

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'evaluation_question');
    }

    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'evaluation_experience')
            ->withPivot('weight');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'evaluation_user')
            ->withPivot('score', 'success');
    }

    public function experienceSessions()
    {
        return $this->belongsToMany(
            Session::class,
            'evaluation_experience_session',
            'evaluation_id',
            'experience_session_id'
        )->where('status', '<>', Evaluation::EVALUATION_EXPERIENCE_STATUS_DELETED)->withPivot('weight');
    }

    public function evaluationUser()
    {
        return $this->hasMany(EvaluationUser::class);
    }

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // SETTERS

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = Master::trimmed($name, true);
    }

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = Master::trimmed($title, true);
    }

    public function setDescriptionAttribute($description)
    {
        $this->attributes['description'] = Master::trimmed($description, true);
    }

    public function setConfigurationsAttribute($configurations)
    {
        $configurations = $configurations ?? [];
        $this->attributes['configurations'] = json_encode($configurations);
    }

    // SCOPES

    public function scopeSearch($query, $terms)
    {
        return $query->where('title', 'like', "%{$terms}%")
            ->orWhere('name', 'like', "%{$terms}%")
            /*->orWhere(function ($q) use ($terms) {
                $q->whereDate('created_at', $terms);
            })*/
            ->orWhereHas('questions', function ($q) use ($terms) {
                $q->where('title', 'like', "%{$terms}%");
            })
            ->orWhereHas('experiences', function ($q) use ($terms) {
                $q->where('title', 'like', "%{$terms}%");
            })
            ->orWhereHas('experienceSessions', function ($q) use ($terms) {
                $q->where('title', 'like', "%{$terms}%");
            })
            ->orWhereHas('users', function ($q) use ($terms) {
                $q->where('name', 'like', "%{$terms}%")
                    ->orWhere('surname', 'like', "%{$terms}%")
                    ->orWhere('email', 'like', "%{$terms}%")
                    ->orWhere(\DB::raw("CONCAT(name,' ',surname)"), 'like', "%{$terms}%");
            });
    }

    // RULES

    public static function storeRules()
    {
        return [
            'name'                      => 'required|string',
            'title'                     => 'required|string',
            'description'               => 'nullable|string',
            'type'                      => 'required|string|in:'.self::inType(),
            'difficulty'                => 'required|string|in:'.self::inDifficulty(),
            'creation_type'             => 'required|string|in:'.self::inCreationType(),
            'configurations'            => 'nullable|array',
            'questions'                 => 'nullable|array',
            'questions.*.question_id'   => 'required|numeric',
            'experiences'               => 'nullable|array',
            'experience_sessions'       => 'nullable|array'
        ];
    }

    public static function bulkStoreRules(): array
    {
        return [
            'name'                                  => 'required|string',
            'title'                                 => 'required|string',
            'description'                           => 'nullable|string',
            'type'                                  => 'required|string|in:'.self::inType(),
            'difficulty'                            => 'required|string|in:'.self::inDifficulty(),
            'creation_type'                         => 'required|string|in:'.self::inCreationType(),
            'configurations'                        => 'nullable|array',
            'questions'                             => 'required|array',
            'questions.*.title'                     => 'required|string',
            'questions.*.image'                     => 'nullable|string',
            'questions.*.type'                      => 'required|string|in:'.Question::inType(),
            'questions.*.difficulty'                => 'required|string|in:'.Question::inDifficulty(),
            'questions.*.configurations'            => 'nullable|array',
            'questions.*.answers'                   => 'required|array',
            'questions.*.answers.*.title'           => 'required|string',
            'questions.*.answers.*.image'           => 'nullable|string',
            'questions.*.answers.*.type'            => 'required|string|in:'.Answer::inType(),
            'questions.*.answers.*.is_correct'      => 'required|boolean',
            'questions.*.answers.*.configurations'  => 'nullable|array',
            'experiences'                           => 'nullable|array',
            'experience_sessions'                   => 'nullable|array'
        ];
    }

    public static function updateRules()
    {
        return [
            'name'                      => 'required|string',
            'title'                     => 'required|string',
            'description'               => 'nullable|string',
            'type'                      => 'required|string|in:'.self::inType(),
            'difficulty'                => 'required|string|in:'.self::inDifficulty(),
            'creation_type'             => 'required|string|in:'.self::inCreationType(),
            'configurations'            => 'nullable|array',
            'questions'                 => 'nullable|array',
            'questions.*.question_id'   => 'required|numeric',
            'experiences'               => 'nullable|array',
            'experience_sessions'       => 'nullable|array'
        ];
    }

    // Relations

    public function activities()
    {
        return $this->hasMany(ExperienceActivity::class);
    }

    public function activity()
    {
        return $this->hasOne(ExperienceActivity::class);
    }

    // FUNCTIONS

    public static function inType()
    {
        return implode(',', self::TYPE);
    }

    public static function inDifficulty()
    {
        return implode(',', self::DIFFICULTY);
    }

    public static function inCreationType()
    {
        return implode(',', self::CREATION_TYPE);
    }
}
