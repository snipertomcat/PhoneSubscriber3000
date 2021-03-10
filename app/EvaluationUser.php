<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;

class EvaluationUser extends Model
{
    public $table = 'evaluation_user';

    protected $casts = [
        'id'            => 'integer',
        'evaluation_id' => 'integer',
        'user_id'       => 'integer',
        'score'         => 'decimal:2',
        'success'       => 'boolean',
        'started_at'    => 'datetime',
        'finished_at'   => 'datetime'
    ];

    public $fillable = [
        'evaluation_id',
        'user_id',
        'score',
        'success',
        'started_at',
        'finished_at'
    ];

    // RELATIONS

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluationAnswerUser()
    {
        return $this->hasMany(EvaluationAnswerUser::class);
    }
}
