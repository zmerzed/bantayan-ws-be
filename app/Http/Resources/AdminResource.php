<?php

namespace App\Http\Resources;

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
        return array_merge(
            parent::toArray($request),
            [
                'role' => $this->getDefaultRole(),
                'barangay' => $this->barangay
            ]
        );
    }

    private function getDefaultRole() {
        
        return $this->roles?->first()?->name;
    }
}
