<?php

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'name' => str_replace('-', ' ', $this->name),
            'year' => $this->year,
            'semester' => $this->semester,
            'is_hidden' => $this->is_hidden,
            'url' => route('academic-charges.show', $this->id),
            'file_path' => Storage::disk('r2')->url($this->path),
        ];
    }
}
