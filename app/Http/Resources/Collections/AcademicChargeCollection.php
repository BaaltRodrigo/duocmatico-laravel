<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\Identifiers\AcademicChargeIdentifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AcademicChargeCollection extends ResourceCollection
{
    public static $wrap = 'charges';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): ResourceCollection
    {
        return AcademicChargeIdentifier::collection($this->collection);
    }
}
