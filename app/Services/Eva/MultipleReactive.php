<?php

namespace Apithy\Services\Eva;

use Apithy\Question;

class MultipleReactive extends Reactive
{
    public function checkAnswers(Question $question, array &$userAnswers): void
    {
        $this->filterUserAnswers($question, $userAnswers);
        if ($this->isStrict($question)) {
            $this->completeAnswers($question, $userAnswers);
        } else {
            $this->partialAnswers($question, $userAnswers);
        }
    }

    private function filterUserAnswers(Question $question, array &$userAnswers) : void
    {
        foreach ($question->answers as $answer) {
            foreach ($userAnswers as &$ua) {
                if ($answer->id == $ua['answer_id']) {
                    if ($answer->is_correct) {
                        $ua['is_correct'] = true;
                    }
                }
            }
        }
    }

    private function partialAnswers(Question $question, array $userAnswers) : void
    {
        $userAnswersCount = collect($userAnswers)
            ->whereIn('answer_id', $question->answers->pluck('id'))
            ->where('is_correct', '=', 1)
            ->count();
        $question->valid = $userAnswersCount > 0;
    }

    private function completeAnswers(Question $question, array $userAnswers) : void
    {
        $original = collect($userAnswers)
            ->whereIn('answer_id', $question->answers->pluck('id'))
            ->count();
        $userAnswersCount = collect($userAnswers)
            ->whereIn('answer_id', $question->answers->pluck('id'))
            ->where('is_correct', '=', 1)
            ->count();
        if ($original <= $userAnswersCount) {
            $answersCount = $question->answers->where('is_correct', 1)->count();
            $question->valid = ($answersCount === $userAnswersCount);
        }
    }

    private function isStrict(Question $question) : bool
    {
        return true;
    }
}
