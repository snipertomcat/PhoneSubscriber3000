<?php

namespace Apithy\Http\Resources\Experiences;

use Illuminate\Http\Resources\Json\Resource;

class ExperienceLicenseTableResource extends Resource
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
            'title' => $this->title,
            'system_id' => $this->system_id,
            'full_path_cover' => $this->getFullPathCoverAttribute(),
            'licenses_count' => $this->licences_count,
            'pending_license_count' => $this->pending_license_count,
            'used_license_count' => $this->used_license_count,
            'unused_license_count' => $this->unused_license_count
        ];
    }
}
