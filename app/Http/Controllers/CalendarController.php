<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSectionRequest;
use App\Http\Requests\StoreCalendarRequest;
use App\Http\Requests\UpdateCalendarRequest;
use App\Models\Calendar;
use App\Models\Section;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request) {
        $user = auth()->user();

        return $user->calendars;
    }

    public function show(Calendar $calendar) {
        return $calendar;
    }

    public function store(StoreCalendarRequest $request) 
    {
        // Authorization handled inside form request
        $validated = $request->validated();
        $calendar = Calendar::create($validated);

        return $calendar;
    }

    public function update(UpdateCalendarRequest $request, Calendar $calendar)
    {
        // Authorization handled inside form request
        $validated = $request->validated();
        $calendar->update($validated);
        return $calendar;
    }

    public function destroy(Calendar $calendar)
    {
        $calendar->delete();
        return response()->json(['message' => 'Calendar deleted'], 204);
    }

    // Section related functions
    public function sections(Calendar $calendar)
    {
        return $calendar->sections;
    }

    public function addSection(AddSectionRequest $request, Calendar $calendar)
    {
        $validated = $request->validated();
        // Check if section exists in calendar
        if ($calendar->sections()->where('id', $validated['section_id'])->exists()) {
            return response()->json(['message' => 'Section already exists in calendar'], 409);
        }

        // Check if section is from same academic charge
        $section = $calendar->academicCharge->sections()->where('id', $validated['section_id'])->first();
        if (!$section) {
            return response()->json(['message' => 'Section does not exist in academic charge'], 404);
        }

        // TODO: Check if calendarable type id is the same as section school or career


        // Attach section
        $calendar->sections()->attach($section);
        
        return response()->json([
            'message' => 'Section added to calendar',
            'calendar' => $calendar
        ], 201);
    }

    public function removeSection(Calendar $calendar, Section $section)
    {
        $calendar->sections()->detach($section);
        return response()->json([
            'message' => 'Section removed from calendar',
            'calendar' => $calendar
        ], 204);
    }
}
