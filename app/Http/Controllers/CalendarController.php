<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSectionRequest;
use App\Http\Requests\StoreCalendarRequest;
use App\Http\Requests\UpdateCalendarRequest;
use App\Http\Resources\CalendarResource;
use App\Http\Resources\Collections\CalendarCollection;
use App\Models\Calendar;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;


class CalendarController extends Controller
{
    public function index(Request $request): CalendarCollection
    {
        $user = auth()->user();

        return new CalendarCollection($user->calendars->load('academicCharge'));
    }

    public function show(Calendar $calendar): CalendarResource
    {
        $calendar->load('academicCharge', 'sections', 'calendarable');
        return new CalendarResource($calendar);
    }

    public function store(StoreCalendarRequest $request): CalendarResource 
    {
        // Authorization handled inside form request
        $validated = $request->validated();
        // For some reason, the passedValidated does not overwrite the calendarable_type
        $validated['calendarable_type'] = 'App\Models\\' . ucfirst($validated['calendarable_type']);
        // Check if validate has uuid or generate one
        if (!isset($validated['uuid'])) {
            $validated['uuid'] = Str::uuid();
        }

        $calendar = Calendar::create($validated + [
            'user_id' => auth()->user()->id
        ]);

        return new CalendarResource($calendar);
    }

    public function update(UpdateCalendarRequest $request, Calendar $calendar): CalendarResource
    {
        // Authorization handled inside form request
        $validated = $request->validated();
        $calendar->update($validated);
        return new CalendarResource($calendar);
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
            return response()->json(['message' => 'Section already exists in calendar'], Response::HTTP_CONFLICT);
        }

        // Check if section is from same academic charge
        $section = $calendar->academicCharge->sections()->where('id', $validated['section_id'])->first();
        if (!$section) {
            return response()->json(['message' => 'Section does not exist in academic charge'], Response::HTTP_NOT_FOUND);
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
        // check if there is a section to detach
        if (!$calendar->sections()->where('id', $section->id)->exists()) {
            return response()->json(['message' => 'Section does not exist in calendar'], Response::HTTP_NOT_FOUND);
        }

        $calendar->sections()->detach($section);
        
        return response()->json([
            'message' => 'Section removed from calendar',
            'calendar' => $calendar
        ], 204);
    }
}
