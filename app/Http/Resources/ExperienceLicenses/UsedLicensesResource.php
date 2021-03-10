<?php

namespace Apithy\Http\Resources\ExperienceLicenses;

use Illuminate\Http\Resources\Json\Resource;

class UsedLicensesResource extends Resource
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
            'avatar' => $this->user->avatarUrl(),
            'name' => $this->user->name,
            'created_at' => $this->created_at->format('d/m/Y'),
            'experience' => $this->experience->getUserProgress($this->user_id, true)
        ];
    }
}
