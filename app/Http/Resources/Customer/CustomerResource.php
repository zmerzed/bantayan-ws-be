<?php

namespace App\Http\Resources\Customer;

use App\Models\Sequence;
use App\Http\Resources\SequenceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
                'barangay' => $this->barangay,
                'details' => $this->details,
                'full_name' => $this->last_name . ", " . $this->first_name . " " . $this->mi,
                'sequence_detail' => SequenceResource::make($this->getSequence())
            ]
        );
    }
}
