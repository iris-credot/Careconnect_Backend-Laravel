<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SportController;

// Routes for doctor and admin roles (both roles)
Route::middleware(['jwt:doctor,admin'])->prefix('sports')->group(function () {
    Route::post('/sports/create', [SportController::class, 'createSportRecommendation']);
    Route::get('/sports/all', [SportController::class, 'getAllSportRecommendations']);
    Route::put('/sports/update/{id}', [SportController::class, 'updateSportRecommendation']);
});

// Routes for only admin role (example, you can customize)
Route::middleware(['jwt:admin'])->prefix('sports')->group(function () {
    Route::delete('/sports/delete/{id}', [SportController::class, 'deleteSportRecommendation']);
    Route::get('/sports/get/{id}', [SportController::class, 'getSportRecommendationById']);
});
