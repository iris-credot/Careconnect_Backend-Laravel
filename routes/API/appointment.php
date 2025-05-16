<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AppointmentController;

// Routes requiring doctor or admin (BothJWT)
Route::middleware(['jwt:doctor,admin'])->prefix('appointment')->group(function () {
    Route::post('/appointments/create', [AppointmentController::class, 'createAppointment']);
    Route::get('/appointments/all', [AppointmentController::class, 'getAllAppointments']);
    Route::put('/appointments/update/{id}', [AppointmentController::class, 'updateAppointment']);
    Route::put('/appointments/appoint/{id}/reply', [AppointmentController::class, 'respondToRescheduleRequest']);
});

// Routes requiring authenticated user (AuthJWT)
Route::middleware(['jwt:doctor,admin'])->prefix('appointment')->group(function () {
    Route::get('/appointments/get/{id}', [AppointmentController::class, 'getAppointmentById']);
    Route::get('/appointments/byPatient/{id}', [AppointmentController::class, 'getAppointmentsByPatientId']);
    Route::get('/appointments/filter', [AppointmentController::class, 'filterAppointments']);
    Route::put('/appointments/status/{id}', [AppointmentController::class, 'changeAppointmentStatus']);
    Route::put('/appointments/appoint/{id}/reschedule', [AppointmentController::class, 'rescheduleAppointment']);
});

// Admin-only route
Route::middleware(['jwt:admin'])->prefix('appointment')->group(function () {
    Route::delete('/appointments/delete/{id}', [AppointmentController::class, 'deleteAppointment']);
});
