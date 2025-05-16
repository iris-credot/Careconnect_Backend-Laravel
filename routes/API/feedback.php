<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FeedbackController;

// Routes accessible to doctor and admin
Route::middleware(['jwt:doctor,admin'])->prefix('feedback')->group(function () {
    // Create new feedback
    Route::post('/feedback/create', [FeedbackController::class, 'createFeedback']);

    // Get all feedback by user
    Route::get('/feedback/user/{userId}', [FeedbackController::class, 'getFeedbackByUser']);

    // Delete feedback
    Route::delete('/feedback/delete/{feedbackId}', [FeedbackController::class, 'deleteFeedback']);
});

// Routes for authenticated doctor/admin (can be split further if needed)
Route::middleware(['jwt:doctor,admin'])->prefix('feedback')->group(function () {
    // Get specific feedback by ID
    Route::get('/feedback/{feedbackId}', [FeedbackController::class, 'getFeedbackById']);

    // Update feedback status
    Route::put('/feedback/status/{feedbackId}', [FeedbackController::class, 'updateFeedbackStatus']);
});
