<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HealthController;

// Routes accessible to doctor and admin
Route::middleware(['jwt:doctor,admin'])->prefix('health')->group(function () {
    // Create a new health record
    Route::post('/create', [HealthController::class, 'createHealthRecord']);

    // Get all health records
    Route::get('/all', [HealthController::class, 'getAllHealthRecords']);

    // Update a health record
    Route::put('/update/{healthId}', [HealthController::class, 'updateHealthRecord']);

    // Delete a health record
    Route::delete('/delete/{healthId}', [HealthController::class, 'deleteHealthRecord']);
});

// Route accessible to authenticated doctor or admin for fetching by patient
Route::middleware(['jwt:doctor,admin'])->prefix('health')->group(function () {
    // Get a health record by patient
    Route::get('/getOne/{healthId}', [HealthController::class, 'getHealthByPatient']);
});
