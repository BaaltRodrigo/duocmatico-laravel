<?php

// Controllers

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\v1\AcademicChargeController;
use App\Http\Controllers\v1\FirebaseAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Firebase example route to check if a token is valid.
 * All auth related routes should be put under here.
 */
Route::prefix('/auth')->group(function () {
    Route::get('/me', [FirebaseAuthController::class, 'me'])
        ->middleware('auth.firebase')
        ->name('auth.current-user');
});

/*
|--------------------------------------------------------------------------
| Academic charge Routes
|--------------------------------------------------------------------------
|
| Some routes for academic charges are protected by the auth.firebase
| middleware in combination of roles. This is because the we don't
| allow every user to upload a file with a whole charge request
|
| There are also nested routes for a charge. This is because we
| want to give more options to the front-end in the way they
| want to get the data. Sections can also be fetched from
| the their respective routes using some query params.
*/

Route::apiResource('academic-charges', AcademicChargeController::class)
    ->only('index', 'show')
    ->parameters(['academic-charges' => 'charge']);
Route::apiResource('academic-charges', AcademicChargeController::class)
    ->only('store', 'update', 'destroy')
    ->middleware('auth.firebase')
    ->parameters(['academic-charges' => 'charge']);

Route::prefix('/academic-charges/{charge}')->group(function () {
    Route::get('/sections', [AcademicChargeController::class, 'sections'])->name('academic-charges.sections');
    Route::get('/careers', [AcademicChargeController::class, 'careers'])->name('academic-charges.careers');
    Route::get('/schools', [AcademicChargeController::class, 'schools'])->name('academic-charges.schools');
});

Route::apiResource('calendars', CalendarController::class)
    ->middleware('auth.firebase')
    ->except('show')
    ->parameters(['calendars' => 'calendar']);

// Show for calendars needs to be outside middleware because there
// are public calendars that don't need to be authenticated
Route::get('/calendars/{calendar}', [CalendarController::class])->name('calendars.show');