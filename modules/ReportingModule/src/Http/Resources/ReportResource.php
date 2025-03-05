<?php

namespace Kolette\Reporting\Http\Resources;

use App\Support\Models;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'report_type' => $this->report_type,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'reporter' => UserResource::make($this->whenLoaded('reporter')),
            'reason' => JsonResource::make($this->whenLoaded('reason')),
            'attachments' => MediaResource::collection($this->whenLoaded('attachments')),
            'reportable' => $this->whenLoaded('reportable', function () {
                return Models::getResource($this->reportable);
            }),
        ];
    }
}
