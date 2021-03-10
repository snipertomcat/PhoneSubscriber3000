<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;

class EvaluationAnswerUser extends Model
{
    public $table = 'evaluation_answer_user';

    protected $casts = [
        'id'                    => 'integer',
        'answer_id'             => 'integer',
        'evaluation_user_id'    => 'integer',
        'points'                => 'decimal:2',
        'is_correct'            => 'boolean',
        'configurations'        => 'array'
    ];

    public $fillable = [
        'answer_id',
        'points',
        'is_correct',
        'configurations'
    ];

    // SCOPES

    public function scopeFilterByEvaId($query, $id)
    {
        return $query->where('evaluation_user_id', $id);
    }
}
