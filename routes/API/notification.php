<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\NotificationController;

// Routes accessible to authenticated users with role doctor or admin
Route::middleware(['jwt:doctor,admin'])->prefix('notification')->group(function () {
    // Get all notifications for a user
    Route::get('/get/{id}', [NotificationController::class, 'getUserNotifications']);

    // Mark a single notification as seen
    Route::put('/{id}/seen', [NotificationController::class, 'markAsSeen']);

    // Delete a notification
    Route::delete('/{id}', [NotificationController::class, 'deleteNotification']);
});
