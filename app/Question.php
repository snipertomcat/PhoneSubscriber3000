<?php

namespace Apithy;

use Apithy\Utilities\Master\Master;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $casts = [
        'id'                => 'integer',
        'company_id'        => 'integer',
        'title'             => 'string',
        'image'             => 'string',
        'type'              => 'string',
        'difficulty'        => 'string',
        'configurations'    => 'array'
    ];

    public $fillable = [
        'title',
        'image',
        'type',
        'difficulty',
        'configurations'
    ];

    const TYPE = ['single', 'multiple', 'bool', 'sort', 'filling', 'click_and_drop'];
    const DIFFICULTY = ['basic', 'intermediate', 'advanced'];

    // RELATIONS

    public function evaluations()
    {
        return $this->belongsToMany(Evaluation::class, 'evaluation_question')
            ->withPivot('created_at', 'updated_at');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // SCOPES

    public function scopeSearch($query, $terms)
    {
        return $query->where('title', 'like', "%{$terms}%");
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

    public static function storeRules()
    {
        $rules = [
            'title'             => 'required|string',
            'image'             => 'nullable|string',
            'type'              => 'required|string|in:'.self::inType(),
            'difficulty'        => 'required|string|in:'.self::inDifficulty(),
            'configurations'    => 'nullable|array',
            'abilities'         => 'nullable|array',
            'tags'              => 'nullable|array',
            'experiences'       => 'nullable|array',
            'experiences.*.id'  => 'required|numeric',
            'evaluations'       => 'nullable|array',
            //'evaluations.*.id'  => 'required|numeric',
        ];
        return array_merge($rules, Answer::storeRules(true));
    }

    public static function updateRules()
    {
        return [
            'title'             => 'required|string',
            'image'             => 'nullable|string',
            'type'              => 'required|string|in:'.self::inType(),
            'difficulty'        => 'required|string|in:'.self::inDifficulty(),
            'configurations'    => 'nullable|array',
            'abilities'         => 'nullable|array',
            'tags'              => 'nullable|array',
            'experiences'       => 'nullable|array',
            'experiences.*.id'  => 'required|numeric',
            'evaluations'       => 'nullable|array',
            //'evaluations.*.id'  => 'required|numeric',
        ];
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
}
