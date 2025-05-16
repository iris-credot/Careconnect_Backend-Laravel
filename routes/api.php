<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\PatientController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\PrescriptionController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\ApiDocController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public Documentation Routes
Route::get('/documentation', [ApiDocController::class, 'index'])->name('l5-swagger.default.api');
Route::get('/docs', [ApiDocController::class, 'docs'])->name('l5-swagger.default.docs');
Route::get('/oauth2-callback', [ApiDocController::class, 'oauth2Callback'])->name('l5-swagger.default.oauth2_callback');

// API Root
Route::get('/', [ApiDocController::class, 'index']);




// Additional route files
require __DIR__.'/api/user.php';
require __DIR__.'/api/sports.php';
require __DIR__.'/api/reports.php';
require __DIR__.'/api/patient.php';
require __DIR__.'/api/notification.php';
require __DIR__.'/api/health.php';
require __DIR__.'/api/food.php';
require __DIR__.'/api/feedback.php';
require __DIR__.'/api/doctor.php';
require __DIR__.'/api/chat.php';
require __DIR__.'/api/appointment.php';
