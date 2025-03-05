<?php

namespace Kolette\Auth\Http\Resources;

use Kolette\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'full_name'  => $this->full_name,
            'email'      => $this->email,
            'roles'      => $this->getRoleNames(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relationship
            'avatar' => MediaResource::make($this->whenLoaded('avatar')),
            'permissions' => JsonResource::collection($this->getAllPermissions())
        ];
    }
}
