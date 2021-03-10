<?php

namespace Apithy\Http\Resources\Reactives;

use Illuminate\Http\Resources\Json\Resource;

class QuestionResource extends Resource
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
            'title'             => $this->title,
            'image'             => $this->image,
            'type'              => $this->type,
            'difficulty'        => $this->difficulty,
            'configurations'    => $this->configurations,
            'answers'           => AnswerResource::collection($this->answers),
            'evaluations'       => EvaluationResource::collection($this->whenLoaded('evaluations'))
        ];
    }
}
