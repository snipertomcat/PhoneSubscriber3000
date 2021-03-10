<?php

namespace Apithy\Http\Resources\Dashboard;

use Apithy\EvaluationUser;
use Illuminate\Http\Resources\Json\Resource;

class EvaluationSessionUserResource extends Resource
{
    private $data = [
        'tries'         => 0,
        'success'       => 0,
        'score'         => 0,
        'created_at'    => null
    ];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'        => $this->id,
            'title'     => $this->title,
            'weight'    => ((isset($this->pivot->weight)) ? $this->pivot->weight : 0)
        ];
        return array_merge($data, $this->getEvaluationUser($request));
    }

    private function getEvaluationUser($request) : array
    {
        $evaluationUser = EvaluationUser::where([
            ['evaluation_id', $this->id],
            ['user_id', $request->input('user_id')]
        ])
            ->orderBy('created_at', 'desc')
            ->get();
        if (!isset($evaluationUser[0])) {
            return $this->data;
        }
        return [
            'tries'         => $evaluationUser->count(),
            'success'       => $evaluationUser[0]->success,
            'score'         => $evaluationUser[0]->score,
            'created_at'    => $evaluationUser[0]->created_at->toDateTimeString(),
            'started_at'    => $this->parseDate($evaluationUser[0]->started_at),
            'finished_at'   => $this->parseDate($evaluationUser[0]->finished_at)
        ];
    }

    private function parseDate($date)
    {
        return ((isset($date)) ? $date->toDateTimeString() : $date);
    }
}
