<?php

namespace App\Http\Resources;

use App\Http\Resources\Collection\SectionCollection;
use App\Http\Resources\Identifiers\AcademicChargeIdentifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $calendarable = explode('\\', $this->calendarable_type);
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'is_public' => $this->is_public,
            'owner_id' => $this->user_id,
            'calendarable' => [
                'id' => $this->calendarable_id,
                'name' => $this->calendarable->name,
                'type' => strtolower(end($calendarable))
            ],
            'academic_charge' => $this->whenLoaded('academicCharge', function () {
                return AcademicChargeIdentifier::make($this->academicCharge);
            }),
            'sections' => SectionCollection::make(
                $this->when(
                    $this->relationLoaded('sections'),
                    $this->sections
                )
            )
        ];
    }
}
