<?php

namespace Apithy\Services\Eva;

use Apithy\Evaluation;
use Illuminate\Database\Eloquent\Collection;

class DefaultEvaluationMethod implements EvaluationMethod
{
    public function evaluate(Evaluation $evaluation, array $userAnswers): array
    {
        $userScore = $this->userScore($evaluation->questions);
        $questions_score = $evaluation->questions_count;
        $data = [
            'score' => 0,
            'success' => 0
        ];
        if ($userScore === 0) {
            return $data;
        }
        if ($questions_score === $userScore) {
            $data['score'] = 1;
            $data['success'] = 1;
            return $data;
        }
        $score = $userScore / $questions_score;
        $data['score'] = $score;
        $data['success'] = $this->isApprove($score);
        return $data;
    }

    public function isApprove(float $score) : int
    {
        return $score >= 0.6;
    }

    public function userScore(Collection $questions): float
    {
        return $questions->where('valid', '=', true)->count();
    }
}
