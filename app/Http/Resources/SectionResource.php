<?php

namespace App\Http\Resources;

use App\Http\Resources\Collections\ScheduleCollection;
use App\Http\Resources\Identifiers\CareerIdentifier;
use App\Http\Resources\Identifiers\SchoolIdentifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'shift' => $this->shift,
            'teacher' => $this->teacher,
            // Relationships
            'schedules' => ScheduleCollection::make(
                $this->when(
                    $this->relationLoaded('schedules'),
                    $this->schedules
                )
            ),
            'subject' => SubjectResource::make(
                $this->when(
                    $this->relationLoaded('subject'),
                    $this->subject
                )
            ),
            'career' => CareerIdentifier::make(
                $this->when(
                    $this->relationLoaded('career'),
                    $this->career
                )
            ),
            'school' => SchoolIdentifier::make(
                $this->when(
                    $this->relationLoaded('school'),
                    $this->school
                )
            )
        ];
    }
}
