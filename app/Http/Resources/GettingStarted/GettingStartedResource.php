<?php

namespace Apithy\Http\Resources\GettingStarted;

use Illuminate\Http\Resources\Json\Resource;

class GettingStartedResource extends Resource
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
            'id' => $this->id,
            'activity_name' => $this->activity_name,
            'title' => $this->title,
            'status' => $this->status,
            'type' => $this->type,
            'content' => $this->content,
            'is_completed' => $this->whenPivotLoaded('user_getting_started_item', isset($this->pivot->status) ? $this->pivot->status : null)
        ];
    }
}
