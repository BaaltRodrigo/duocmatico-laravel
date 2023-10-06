<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCalendarRequest;
use App\Http\Requests\UpdateCalendarRequest;
use App\Models\Calendar;
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
}
