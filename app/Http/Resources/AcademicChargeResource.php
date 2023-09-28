<?php

namespace App\Http\Resources;

use App\Http\Resources\Collections\CareerCollection;
use App\Http\Resources\Collections\SchoolCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicChargeResource extends JsonResource
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
            'name' => $this->name,
            'year' => $this->year,
            'semester' => $this->semester,
            'is_hidden' => $this->is_hidden,
            'full_name' => "{$this->name} {$this->year}-{$this->semester}",
            // relationships when loaded
            'careers' => CareerCollection::make(
                $this->when(
                    $this->relationLoaded('sections'),
                    $this->sections->pluck('career')->unique() // Career is always eager loaded on subjects
                )
            ),
            'schools' => SchoolCollection::make(
                $this->when(
                    $this->relationLoaded('sections'),
                    $this->sections->pluck('school')->unique() // School is always eager loaded on subjects
                )
            ),
        ];
    }
}
