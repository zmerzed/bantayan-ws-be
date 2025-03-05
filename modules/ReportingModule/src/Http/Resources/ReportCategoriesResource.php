<?php

namespace Kolette\Reporting\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportCategoriesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'type' => $this->type,
        ];
    }
}
