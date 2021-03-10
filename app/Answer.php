<?php

namespace Apithy;

use Apithy\Utilities\Master\Master;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $casts = [
        'id'                => 'integer',
        'title'             => 'string',
        'image'             => 'string',
        'type'              => 'string',
        'is_correct'        => 'boolean',
        'question_id'       => 'integer',
        'configurations'    => 'array',
        'points'            => 'integer'
    ];

    public $fillable = [
        'title',
        'image',
        'type',
        'is_correct',
        'question_id',
        'points',
        'configurations'
    ];

    const TYPE = ['radio', 'checkbox', 'select', 'input'];

    // RELATIONS

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function evaluationAnswerUser()
    {
        return $this->hasOne(EvaluationAnswerUser::class);
    }

    // SETTERS

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = Master::trimmed($title, true);
    }

    public function setConfigurationsAttribute($configurations)
    {
        $configurations = $configurations ?? [];
        $this->attributes['configurations'] = json_encode($configurations);
    }

    // RULES

    public static function storeRules($forArray = false)
    {
        if ($forArray) {
            return [
                'answers'                       => 'required|array',
                'answers.*.title'               => 'required|string',
                'answers.*.image'               => 'nullable|string',
                'answers.*.type'                => 'required|string|in:'.self::inType(),
                'answers.*.is_correct'          => 'required|boolean',
                'answers.*.configurations'      => 'nullable|array'
            ];
        }
        return [
            'title'                 => 'required|string',
            'image'                 => 'nullable|string',
            'type'                  => 'required|string|in:'.self::inType(),
            'is_correct'            => 'required|boolean',
            'question_id'           => 'required|numeric',
            'configurations'        => 'nullable|array'
        ];
    }

    public static function updateRules($forArray = false)
    {
        if ($forArray) {
            return [
                'answers'                       => 'required|array',
                'answers.*.title'               => 'required|string',
                'answers.*.image'               => 'nullable|string',
                'answers.*.type'                => 'required|string|in:'.self::inType(),
                'answers.*.is_correct'          => 'required|boolean',
                'answers.*.configurations'      => 'nullable|array'
            ];
        }
        return [
            'title'                 => 'required|string',
            'image'                 => 'nullable|string',
            'type'                  => 'required|string|in:'.self::inType(),
            'is_correct'            => 'required|boolean',
            'question_id'           => 'required|numeric',
            'configurations'        => 'nullable|array'
        ];
    }

    public static function destroyRules($forArray = false)
    {
        if ($forArray) {
            return [
                'answers'                   => 'required|array',
                'answers.*.question_id'     => 'required|numeric'
            ];
        }
        return [
            'question_id' => 'required|numeric'
        ];
    }

    // FUNCTIONS

    public static function inType()
    {
        return implode(',', self::TYPE);
    }
}
