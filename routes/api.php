<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CareerController;
use App\Http\Controllers\UploadAcademicChargeController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\CourseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1')->group(function () {
    Route::post('academic-charge', UploadAcademicChargeController::class);

    Route::apiResource('careers', CareerController::class)->only(['index']);
    Route::apiResource('campuses', CampusController::class)->only(['index']);
    Route::apiResource('courses', CourseController::class)->only(['index']);
});