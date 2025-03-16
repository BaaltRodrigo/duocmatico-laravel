<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Resources\CourseCollection;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['sections','sections.schedules'])->get();
        $resources = new CourseCollection($courses);

        return $resources;
    }
}
