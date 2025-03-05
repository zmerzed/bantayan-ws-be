<?php

namespace Kolette\Category\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'type' => $this->type,
            'category' => CategoryResource::collection($this->whenLoaded('category')),
            'subcategories' => CategoryResource::collection($this->whenLoaded('subcategories')),
        ];
    }
}
