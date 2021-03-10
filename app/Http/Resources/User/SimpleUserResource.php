<?php

namespace Apithy\Http\Resources\User;

use Apithy\User;
use Illuminate\Http\Resources\Json\Resource;

class SimpleUserResource extends Resource
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
            'name' => "{$this->name} {$this->surname}",
            'is_active' => $this->activated && $this->status === User::STATUS_ACTIVE,
            'licences' => $this->licences
        ];
    }
}
