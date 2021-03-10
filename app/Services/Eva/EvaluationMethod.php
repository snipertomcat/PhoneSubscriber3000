<?php

namespace Apithy\Services\Eva;

use Apithy\Evaluation;

interface EvaluationMethod
{
    public function evaluate(Evaluation $evaluation, array $userAnswers): array;
    public function isApprove(float $score) : int;
}
