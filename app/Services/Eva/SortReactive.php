<?php

namespace Apithy\Services\Eva;

use Apithy\Question;
use Illuminate\Database\Eloquent\Collection;

class SortReactive extends Reactive
{
    public function checkAnswers(Question $question, array &$userAnswers): void
    {
        $answers = $this->sortModelAnswers($question->answers);
        if ($this->compareSort($answers, $userAnswers)) {
            $question->valid = true;
            foreach ($answers as $answer) {
                foreach ($userAnswers as &$ua) {
                    if ($answer->id == $ua['answer_id']) {
                        if ($answer->is_correct) {
                            $ua['is_correct'] = true;
                        }
                    }
                }
            }
        } else {
            $question->valid = false;
        }
    }

    private function compareSort(Collection $answers, array $userAnswers) : bool
    {
        $userAnswers = collect($userAnswers)->whereIn('answer_id', $answers->pluck('id'))->values();
        $userAnswers = $this->sortUserAnswer($userAnswers);

        if ($userAnswers->count() != $answers->count()) {
            return false;
        }

        foreach ($answers as $key => $answer) {
            if ($answer->id != $userAnswers[$key]['answer_id']) {
                return false;
            }
        }
        return true;
    }

    private function sortUserAnswer(\Illuminate\Support\Collection $userAnswers) : \Illuminate\Support\Collection
    {
        return $userAnswers->sortBy(function ($answer, $key) {
            return $answer['configurations']['order']['weight'];
        })->values();
    }

    private function sortModelAnswers(Collection $answers) : Collection
    {
        return $answers->sortBy(function ($answer, $key) {
            return $answer['configurations']['order']['weight'];
        })->values();
    }
}
