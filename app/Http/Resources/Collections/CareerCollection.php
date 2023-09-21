<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\Identifiers\CareerIdentifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CareerCollection extends ResourceCollection
{
    public static $wrap = 'careers';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): ResourceCollection
    {
        return CareerIdentifier::collection($this->collection);
    }
}
