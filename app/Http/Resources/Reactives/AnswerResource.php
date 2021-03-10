<?php

namespace Apithy\Http\Resources\Reactives;

use Illuminate\Http\Resources\Json\Resource;

class AnswerResource extends Resource
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
            'is_correct'        => $this->is_correct,
            'points'            => $this->points,
            'configurations'    => $this->configurations,
            'question'          => QuestionResource::make($this->whenLoaded('question')),
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'answer_user'       => AnswerUserResource::make($this->whenLoaded('evaluationAnswerUser'))
        ];
    }
}
