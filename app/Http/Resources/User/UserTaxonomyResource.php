<?php

namespace Apithy\Http\Resources\User;

use Apithy\Http\Resources\Taxonomy\TaxonomyResource;
use Illuminate\Http\Resources\Json\Resource;

class UserTaxonomyResource extends Resource
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
            'full_name' => "{$this->name} {$this->surname}",
            'taxonomy' => TaxonomyResource::collection($this->taxonomy)
        ];
    }
}
