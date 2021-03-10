<?php

namespace Apithy\Http\Resources\Reactives;

use Illuminate\Http\Resources\Json\Resource;

class EvaluationResource extends Resource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'title'             => $this->title,
            'type'              => $this->type,
            'difficulty'        => $this->difficulty,
            $this->mergeWhen(isset($this->pivot->weight), function () {
                return ['weight' => $this->pivot->weight];
            }),
            'activity'          => $this->activity->id,
            'configurations'    => $this->configurations,
            'questions'         => QuestionResource::collection($this->whenLoaded('questions')),
            'created_at'        => $this->created_at,
            'min_score'         => $this->min_score,
            'updated_at'        => $this->updated_at,
        ];
    }
}
