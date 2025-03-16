<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return CourseResource::collection($this->collection)->toArray($request);
    }

    public function with($request): array
    {
        return [
            'total_courses' => $this->collection->count(),
        ];
    }

    public static $wrap = 'courses';
}
