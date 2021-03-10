<?php

namespace Apithy\Services\Eva;

use Apithy\Question;

interface ReactiveEvaluation
{
    public function checkAnswers(Question $question, array &$userAnswers) : void;
}
