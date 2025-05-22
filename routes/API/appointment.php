<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AppointmentController;

// Routes requiring doctor or admin (BothJWT)
Route::middleware(['jwt:doctor,admin'])->prefix('appointment')->group(function () {
    Route::post('/create', [AppointmentController::class, 'createAppointment']);
    Route::get('/all', [AppointmentController::class, 'getAllAppointments']);
    Route::put('/update/{id}', [AppointmentController::class, 'updateAppointment']);
    Route::put('/appoint/{id}/reply', [AppointmentController::class, 'respondToRescheduleRequest']);
});

// Routes requiring authenticated user (AuthJWT)
Route::middleware(['jwt:doctor,admin'])->prefix('appointment')->group(function () {
    Route::get('/get/{id}', [AppointmentController::class, 'getAppointmentById']);
    Route::get('/byPatient/{id}', [AppointmentController::class, 'getAppointmentsByPatientId']);
    Route::get('/filter', [AppointmentController::class, 'filterAppointments']);
    Route::put('/status/{id}', [AppointmentController::class, 'changeAppointmentStatus']);
    Route::put('/appoint/{id}/reschedule', [AppointmentController::class, 'rescheduleAppointment']);
});

// Admin-only route
Route::middleware(['jwt:admin'])->prefix('appointment')->group(function () {
    Route::delete('/delete/{id}', [AppointmentController::class, 'deleteAppointment']);
});
