<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\IntakeCase */
class IntakeCaseResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'alias' => $this->alias,
            'contact_channel' => $this->contact_channel->value,
            'contact_channel_label' => $this->contact_channel->label(),
            'case_type' => $this->case_type->value,
            'case_type_label' => $this->case_type->label(),
            'priority' => $this->priority->value,
            'priority_label' => $this->priority->label(),
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'internal_note' => $this->internal_note,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'events' => CaseEventResource::collection($this->whenLoaded('events')),
        ];
    }
}
