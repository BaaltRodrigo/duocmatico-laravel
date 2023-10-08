<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\Identifiers\CalendarIdentifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CalendarCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return CalendarIdentifier::collection($this->collection)->toArray($request);
    }
}
