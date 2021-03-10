<?php

namespace Apithy\Services\Eva;

use Apithy\Question;
use Illuminate\Database\Eloquent\Collection;

class FillingTheBlankReactive extends Reactive
{
    public function checkAnswers(Question $question, array &$userAnswers): void
    {
        $answers = $this->sortModelAnswers($question->answers);
        if ($this->checkFilling($answers, $userAnswers)) {
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

    private function checkFilling(Collection $answers, array $userAnswers) : bool
    {
        $filteredUserAnswers = $this->filterUserAnswers($answers, $userAnswers);
        if ($filteredUserAnswers->count() != $answers->where('is_correct', '=', 1)->count()) {
            return false;
        }

        foreach ($answers as $answer) {
            foreach ($filteredUserAnswers as $ua) {
                if ($answer->is_correct) {
                    if ($answer['configurations']['filling']['weight'] == $ua['configurations']['filling']['weight']) {
                        if (!$this->compareStrings(
                            $answer['configurations']['filling']['text'],
                            $ua['configurations']['filling']['text']
                        )) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    private function compareStrings(string $str1, string $str2) : bool
    {
        $str1 = mb_strtolower($str1, 'UTF-8');
        $str2 = mb_strtolower($str2, 'UTF-8');
        return strcmp($str1, $str2) == 0;
    }

    private function filterUserAnswers(Collection $answers, array $userAnswers) : \Illuminate\Support\Collection
    {
        return collect($userAnswers)->whereIn('answer_id', $answers->pluck('id'))->values();
    }


    private function sortModelAnswers(Collection $answers) : Collection
    {
        return $answers->sortBy(function ($answer, $key) {
            return $answer['configurations']['filling']['weight'];
        })->values();
    }
}
