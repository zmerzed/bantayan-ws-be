<?php

namespace App\Http\Resources;

use App\Http\Resources\AdminResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SequenceResource extends JsonResource
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
                'reader' => $this->reader,
                'barangay' => $this->barangay
            ]
        );
    }
}
