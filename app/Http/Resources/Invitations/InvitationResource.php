<?php

namespace Apithy\Http\Resources\Invitations;

use Apithy\Http\Resources\Experiences\ExperienceResource;
use Illuminate\Http\Resources\Json\Resource;

class InvitationResource extends Resource
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
            'contact' => $this->contact,
            'role' => $this->role->name,
            'role_css' => $this->role->getRoleColorClass(),
            'company' => $this->company->name,
            'host_user' => $this->user->full_name,
            'created_at' => $this->created_at->format('d/m/Y'),
            'status' => $this->status,
            'status_name' => (($this->status) ? 'Aceptada' : 'Pendiente'),
            'status_css' => $this->getStatusCssColor(),
            'experience' => ExperienceResource::make($this->experience)
        ];
    }
}
