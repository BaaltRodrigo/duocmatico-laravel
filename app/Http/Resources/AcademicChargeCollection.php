<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AcademicChargeCollection extends ResourceCollection
{
    // Changed the default wrap
    public static $wrap = 'charges';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return AcademicChargeResource::collection($this->collection)
            ->toArray($request);
    }
}
