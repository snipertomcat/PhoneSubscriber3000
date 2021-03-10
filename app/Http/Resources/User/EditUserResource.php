<?php

namespace Apithy\Http\Resources\User;

use Apithy\Http\Resources\Company\CompanyResource;
use Apithy\Http\Resources\Role\RoleResource;
use Apithy\Http\Resources\Taxonomy\TaxonomyResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class EditUserResource extends Resource
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
            'owner' => ($this->id === $request->user()->id),
            'name' => $this->name,
            'surname' => $this->surname,
            'personal_code' => $this->personal_code,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'academic_history' => $this->academic_history,
            'work_history' => $this->work_history,
            'avatar' => $this->avatarUrl(),
            'company' => CompanyResource::make($this->company[0]),
            'role' => RoleResource::make($this->roles[0]),
            'taxonomy_areas' => TaxonomyResource::collection($this->taxonomyArea),
            'taxonomy_abilities' => TaxonomyResource::collection($this->taxonomyAbility),
            'taxonomy_teams' => TaxonomyResource::collection($this->taxonomyTeams),
            'taxonomy_positions' => TaxonomyResource::collection($this->taxonomyPosition),
            'taxonomy_certifications' => TaxonomyResource::collection($this->taxonomyCertifications),
            'taxonomy_customs' => TaxonomyResource::collection($this->taxonomyCustoms),
        ];
    }
}
