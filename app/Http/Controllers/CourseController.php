<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Resources\CourseCollection;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // this route will get 2 query params as mandatory
        // 1. The year we need to fetch the course sections
        // 2. The season of the year we need to fetch the course sections
        // Example: /api/courses?year=2021&season=1
        // This will return all the courses with their sections and schedules for the year 2021 and season 1

        $year = $request->input('year');
        $season = $request->input('season');
        $career = $request->input('career');

        // There is a third optional param of "career" tho filter if a section belongs to a specific career
        // Example: /api/courses?year=2021&season=1&career=1

        $courses = Course::with(['sections.schedules'])->whereHas('sections', function ($query) use ($year, $season, $career) {
            $query->where('year', $year)->where('season', $season);
            if ($career) {
                $query->whereHas('careers', function($q) use ($career) {
                    $q->where('careers.id', $career);
                });
            }
        })->get();

        $resources = new CourseCollection($courses);

        return $resources;
    }
}
