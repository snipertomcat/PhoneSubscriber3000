<?php

namespace Apithy\Http\Resources\ExperienceLicenses;

use Illuminate\Http\Resources\Json\Resource;

class PendingLicensesResource extends Resource
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
            'avatar' => 'https://www.gravatar.com/avatar/7238036e52045c108d1126a19c9f7832?d=mp&size=512',
            'email' => $this->email ?? $this->phone,
            'created_at' => $this->created_at->format('d/m/Y')
        ];
    }
}
