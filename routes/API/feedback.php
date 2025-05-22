<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FeedbackController;

// Routes accessible to doctor and admin
Route::middleware(['jwt:doctor,admin'])->prefix('feedback')->group(function () {
    // Create new feedback
    Route::post('/create', [FeedbackController::class, 'createFeedback']);

    // Get all feedback by user
    Route::get('/user/{userId}', [FeedbackController::class, 'getFeedbackByUser']);

    // Delete feedback
    Route::delete('/delete/{feedbackId}', [FeedbackController::class, 'deleteFeedback']);
});

// Routes for authenticated doctor/admin (can be split further if needed)
Route::middleware(['jwt:doctor,admin'])->prefix('feedback')->group(function () {
    // Get specific feedback by ID
    Route::get('/{feedbackId}', [FeedbackController::class, 'getFeedbackById']);

    // Update feedback status
    Route::put('/status/{feedbackId}', [FeedbackController::class, 'updateFeedbackStatus']);
});
