<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\v1\AcademicChargeController;
use App\Http\Controllers\v1\FirebaseAuthController;

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
    Route::get('/me', [FirebaseAuthController::class, 'me'])->middleware('auth.firebase');
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