<?php

namespace Apithy\Services\Eva;

use Apithy\Question;

class Reactive implements ReactiveEvaluation
{
    public function checkAnswers(Question $question, array &$userAnswers): void
    {
        foreach ($question->answers as $answer) {
            foreach ($userAnswers as &$ua) {
                if ($answer->id == $ua['answer_id']) {
                    if ($answer->is_correct) {
                        $ua['is_correct'] = true;
                        $question->valid = true;
                    }
                }
            }
        }
    }
}
