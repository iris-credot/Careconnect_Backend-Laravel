<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FoodController;

// Routes accessible to doctor and admin
Route::middleware(['jwt:doctor,admin'])->prefix('food')->group(function () {
    // Create a new food recommendation
    Route::post('/foods/create', [FoodController::class, 'createFoodRecommendation']);

    // Get all food recommendations
    Route::get('/foods/all', [FoodController::class, 'getAllRecommendations']);

    // Update a food recommendation
    Route::put('/foods/update/{id}', [FoodController::class, 'updateRecommendation']);
});

// Routes accessible to authenticated doctor or admin
Route::middleware(['jwt:doctor,admin'])->prefix('food')->group(function () {
    // Get a specific food recommendation by ID
    Route::get('/foods/get/{id}', [FoodController::class, 'getRecommendationById']);

    // Delete a food recommendation
    Route::delete('/foods/delete/{id}', [FoodController::class, 'deleteRecommendation']);
});
