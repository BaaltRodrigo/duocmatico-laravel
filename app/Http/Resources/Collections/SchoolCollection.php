<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\Identifiers\SchoolIdentifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SchoolCollection extends ResourceCollection
{
    public static $wrap = 'schools';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): ResourceCollection
    {
        return SchoolIdentifier::collection($this->collection);
    }
}
