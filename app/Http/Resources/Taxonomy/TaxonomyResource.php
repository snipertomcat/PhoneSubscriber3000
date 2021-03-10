<?php

namespace Apithy\Http\Resources\Taxonomy;

use Apithy\Http\Resources\User\SimpleUserResource;
use Illuminate\Http\Resources\Json\Resource;

class TaxonomyResource extends Resource
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
            'color' => $this->color,
            'type' => $this->type,
            'users' => $this->when($request->has('user_details'), function () {
                return SimpleUserResource::collection($this->users);
            }),
            'icon' => $this->getIcon($this->type)
        ];
    }

    public function getIcon($type) {
        switch ($type) {
            case 'ability':
                return ['class' => 'icon-vector-grow', 'code' => '&#xe916;'];
            case 'certifications':
                return ['class' => 'icon-certificate', 'code' => '&#xe917;'];
            case 'teams':
                return ['class' => 'icon-connected-user', 'code' => '&#xe918;'];
            case 'custom':
                return ['class' => 'icon-asterisc', 'code' => '&#xe919;'];
            case 'tag':
                return ['class' => 'icon-tag', 'code' => '&#xe912;'];
            case 'company_area':
                return ['class' => 'icon-folder-tree', 'code' => '&#xe915;'];
            case 'company_position':
                return ['class' => 'icon-folder-tree', 'code' => '&#xe915;'];
            default:
                return ['class' => '', 'code' => ''];
        }
    }
}
