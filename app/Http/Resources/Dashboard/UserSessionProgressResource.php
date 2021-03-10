<?php

namespace Apithy\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\Resource;

class UserSessionProgressResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $variable = $this->fuckingScore();
        $data = [
            'title'         => $this->session->title,
            'childs'        => UserSessionChildrenProgressResource::collection($this->session->childs),
            //'score'         => $this->score,
            //'success'       => $this->success,
            'json_data'     => $this->session->json_data,
            'started_at'    => $this->started_at,
            'finished_at'   => $this->finished_at,
            'activities'    => EvaluationSessionUserResource::collection(
                $this->session->evaluations()->orderBy('weight', 'desc')->get()
            )
        ];
        return array_merge($data, $variable);
    }

    private function fuckingScore()
    {
        if (!isset($this->session->childs[0])) {
            return ['score' => $this->score, 'success' => $this->success];
        }
        $data = [];
        $counter = 0;
        $total = 0;

        foreach ($this->session->childs as $index => $child) {
            if (isset($child->evaluations[0])) {
                $counter++;
                $total += $child->progress()->where('enrollment_id',$this->enrollment_id)->first()->score;
            }
        }
        if ($total !== 0) {
            $data['raw_points'] = $total;
            $total = $total / $counter;
        }

        $data['score'] = $total;
        $data['success'] = $total > 0.6;
        return $data;
    }
}
