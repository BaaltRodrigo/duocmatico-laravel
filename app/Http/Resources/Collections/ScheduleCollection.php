<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\ScheduleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ScheduleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ScheduleResource::collection($this->collection)->toArray($request);
    }
}
