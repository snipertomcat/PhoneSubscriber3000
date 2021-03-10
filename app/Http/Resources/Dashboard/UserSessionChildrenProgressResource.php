<?php

namespace Apithy\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\Resource;

class UserSessionChildrenProgressResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $progress = $this->progress()->where('enrollment_id', $request->input('enrollment_id'))->first();
        return [
            'title'         => $this->title,
            'score'         => $progress->score,
            'success'       => $progress->success,
            'json_data'     => $this->json_data,
            'started_at'    => $progress->started_at,
            'finished_at'   => $progress->finished_at,
            'activities'    => EvaluationSessionUserResource::collection(
                $this->evaluations()->orderBy('weight', 'desc')->get()
            )
        ];
    }

    private function theCondition()
    {
        return $this->status == 1 || $this->status == 3 && $this->started_at == null;
    }
}
