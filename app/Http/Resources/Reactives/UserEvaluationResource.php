<?php

namespace Apithy\Http\Resources\Reactives;

use Illuminate\Http\Resources\Json\Resource;

class UserEvaluationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'score'         => $this->score,
            'success'       => $this->success,
            'started_at'    => $this->started_at,
            'finished_at'   => $this->finished_at,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'user'          => EvaUserInformationResource::make($this->whenLoaded('user')),
            'evaluation'    => EvaluationResource::make($this->evaluation)
        ];
    }
}
