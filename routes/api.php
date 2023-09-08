<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
 * The first one check the token with a middleware
 * The second check inside the controller
 */
Route::prefix('/auth')->group(function() {
    Route::post('/me', [FirebaseAuthController::class, 'me'])->middleware('auth.firebase');
    Route::post('/check', [FirebaseAuthController::class, 'checkToken']);
});
