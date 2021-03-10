<?php

namespace Apithy\Http\Resources\Reactives;

use Illuminate\Http\Resources\Json\Resource;

class AnswerUserResource extends Resource
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
            'answer_id'         => $this->answer_id,
            'is_correct'        => $this->is_correct,
            'configurations'    => $this->configurations,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
