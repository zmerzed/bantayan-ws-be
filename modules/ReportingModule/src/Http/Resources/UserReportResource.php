<?php

namespace Kolette\Reporting\Http\Resources;

use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'report_type' => $this->reportable_type,
            'reported_at' => $this->created_at,
            'reported_by' => $this->reported_by,
            'reason_id' => $this->reason_id,
            //
            'reported' => UserResource::make($this->whenLoaded('reportable')),
            'reporter' => UserResource::make($this->whenLoaded('reporter')),
            'reason' => JsonResource::make($this->whenLoaded('reason')),
            'attachments' => MediaResource::collection($this->whenLoaded('attachments')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
