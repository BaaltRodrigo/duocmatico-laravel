<?php

namespace App\Http\Resources;

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
            'year' => $this->year,
            'code' => $this->code,
            'shift' => $this->shift,
            'season' => $this->season,
            'schedules' => ScheduleResource::collection($this->schedules),
            'careers' => CareerIdentifierResource::collection($this->careers),
            'campuses' => CampusIdentifierResource::collection($this->campuses),
        ];
    }
}
