<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PatientController;

Route::prefix('patient')->group(function () {
// Public route: Create a patient (no middleware)
Route::post('/patients/create', [PatientController::class, 'createPatient']);

// Routes accessible to both doctor and admin
Route::middleware(['jwt:doctor,admin'])->group(function () {
    Route::get('/patients/all', [PatientController::class, 'getAllPatients']);
    Route::put('/patients/profile/{id}', [PatientController::class, 'updatePatient']);
    Route::get('/patients/getPatient/{id}', [PatientController::class, 'getPatientById']);
    Route::get('/patients/getPatientByUser/{userId}', [PatientController::class, 'getPatientByUserId']);
    Route::delete('/patients/delete/{id}', [PatientController::class, 'deletePatient']);
});
});
