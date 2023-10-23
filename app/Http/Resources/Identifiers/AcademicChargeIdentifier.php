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
            'name' => str_replace('-', ' ', $this->name),
            'season' => "{$this->year}-{$this->semester}",
            'is_hidden' => $this->is_hidden,
            'url' => route('academic-charges.show', $this->id),
        ];
    }
}
