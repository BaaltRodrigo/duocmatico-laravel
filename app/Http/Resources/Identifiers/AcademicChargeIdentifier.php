<?php

namespace App\Http\Resources\Identifiers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicChargeIdentifier extends JsonResource
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
            'full_name' => "{$this->name} {$this->year}-{$this->semester}",
            'url' => route('academic-charges.show', $this->id),
        ];
    }
}
