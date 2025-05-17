<?php

namespace App\Http\Resources;

use App\Models\Admin;
use App\Http\Resources\AdminResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Customer\CustomerResource;

class ReadingResource extends JsonResource
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
                'customer' => CustomerResource::make($this->customer),
                'readed_by' => AdminResource::make($this->readedBy)
            ]
        );
    }
}
