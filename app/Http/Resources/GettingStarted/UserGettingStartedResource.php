<?php

namespace Apithy\Http\Resources\GettingStarted;

use Illuminate\Http\Resources\Json\Resource;

class UserGettingStartedResource extends Resource
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
            'progress' => $this->getting_started_progress,
            'is_completed' => $this->getting_started_status,
            'activity_data' => GettingStartedResource::collection($this->userGettingStartedItem)
        ];
    }
}
