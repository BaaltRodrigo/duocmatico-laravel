<?php

namespace App\Http\Resources\Identifiers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarIdentifier extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get only last part of calendarable type
        $calendarable_type = explode('\\', $this->calendarable_type);
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'calendarable_type' => end($calendarable_type),
            'calendarable' => [
                'id' => $this->calendarable_id,
                'name' => $this->calendarable->name,
            ],
            'academic_charge' => $this->whenLoaded('academicCharge', function () {
                return AcademicChargeIdentifier::make($this->academicCharge);
            }),
            'sections' => []
        ];
    }
}
