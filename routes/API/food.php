<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FoodController;

// Routes accessible to doctor and admin
Route::middleware(['jwt:doctor,admin'])->prefix('food')->group(function () {
    // Create a new food recommendation
    Route::post('/create', [FoodController::class, 'createFoodRecommendation']);

    // Get all food recommendations
    Route::get('/all', [FoodController::class, 'getAllRecommendations']);

    // Update a food recommendation
    Route::put('/update/{id}', [FoodController::class, 'updateRecommendation']);
});

// Routes accessible to authenticated doctor or admin
Route::middleware(['jwt:doctor,admin'])->prefix('food')->group(function () {
    // Get a specific food recommendation by ID
    Route::get('/get/{id}', [FoodController::class, 'getRecommendationById']);

    // Delete a food recommendation
    Route::delete('/delete/{id}', [FoodController::class, 'deleteRecommendation']);
});
