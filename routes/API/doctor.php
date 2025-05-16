<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DoctorController;

Route::prefix('doctor')->group(function () {
// Public route to create a doctor
Route::post('/doctors/create', [DoctorController::class, 'createDoctor']);

// Routes requiring doctor or admin roles
Route::middleware(['jwt:doctor,admin'])->group(function () {
    // Get all doctors
    Route::get('/doctors/all', [DoctorController::class, 'getAllDoctors']);

    // Update a doctor
    Route::put('/doctors/put/{id}', [DoctorController::class, 'updateDoctor']);

    // Get doctor patients
    Route::get('/doctors/getDoctorPatients/{doctorId}', [DoctorController::class, 'getDoctorPatients']);
});

// Routes restricted to admin only
Route::middleware(['jwt:admin'])->group(function () {
    // Delete a doctor
    Route::delete('/doctors/delete/{id}', [DoctorController::class, 'deleteDoctor']);

    // Get a doctor by ID
    Route::get('/doctors/getdoctor/{id}', [DoctorController::class, 'getDoctorById']);
});
});
