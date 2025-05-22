<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PatientController;

Route::prefix('patient')->group(function () {
// Public route: Create a patient (no middleware)
Route::post('/create', [PatientController::class, 'createPatient']);

// Routes accessible to both doctor and admin
Route::middleware(['jwt:doctor,admin'])->group(function () {
    Route::get('/all', [PatientController::class, 'getAllPatients']);
    Route::put('/profile/{id}', [PatientController::class, 'updatePatient']);
    Route::get('/getPatient/{id}', [PatientController::class, 'getPatientById']);
    Route::get('/getPatientByUser/{userId}', [PatientController::class, 'getPatientByUserId']);
    Route::delete('/delete/{id}', [PatientController::class, 'deletePatient']);
});
});
