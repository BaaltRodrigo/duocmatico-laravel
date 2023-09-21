<?php

// Controllers
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
Route::prefix('/auth')->group(function() {
    Route::get('/me', [FirebaseAuthController::class, 'me'])
        ->middleware('auth.firebase')
        ->name('auth.current-user');
});


/**
 * Academic charges routes.
 */

Route::apiResource('academic-charges', AcademicChargeController::class)
    ->only('index', 'show')
    ->parameters(['academic-charges' => 'charge']);
Route::apiResource('academic-charges', AcademicChargeController::class)
    ->only('store', 'update', 'destroy')
    ->middleware('auth.firebase')
    ->parameters(['academic-charges' => 'charge']);

// Nested routes for academic charges.

Route::get('/academic-charges/{charge}/careers', [AcademicChargeController::class, 'careers'])
    ->name('academic-charges.careers.index');

Route::get('/academic-charges/{charge}/schools', [AcademicChargeController::class, 'schools'])
    ->name('academic-charges.schools.index');
