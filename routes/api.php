<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\PatientController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\PrescriptionController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\DocumentationController;
use App\Http\Controllers\API\ApiDocController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\SportController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SwaggerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Documentation Routes
Route::get('/documentation', [ApiDocController::class, 'index'])->name('l5-swagger.default.api');
Route::get('/docs', [ApiDocController::class, 'docs'])->name('l5-swagger.default.docs');
Route::get('/oauth2-callback', [ApiDocController::class, 'oauth2Callback'])->name('l5-swagger.default.oauth2_callback');

// API Root
Route::get('/', [ApiDocController::class, 'index']);

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Patient Routes
Route::prefix('patient')->group(function () {
    Route::post('/create', [PatientController::class, 'create']);
    Route::get('/all', [PatientController::class, 'all']);
    Route::get('/getPatient/{id}', [PatientController::class, 'getPatient']);
    Route::get('/getPatientByUser/{id}', [PatientController::class, 'getPatientByUser']);
    Route::put('/profile/{id}', [PatientController::class, 'updateProfile']);
    Route::delete('/delete/{id}', [PatientController::class, 'delete']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Doctor Routes
    Route::apiResource('doctors', DoctorController::class);
    Route::get('/doctors/{doctor}/appointments', [DoctorController::class, 'appointments']);
    Route::get('/doctors/{doctor}/prescriptions', [DoctorController::class, 'prescriptions']);

    // Appointment Routes
    Route::apiResource('appointments', AppointmentController::class);
    Route::put('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);

    // Prescription Routes
    Route::apiResource('prescriptions', PrescriptionController::class);

    // Message Routes
    Route::apiResource('messages', MessageController::class);
    Route::get('/messages/conversation/{user}', [MessageController::class, 'conversation']);
    Route::put('/messages/{message}/read', [MessageController::class, 'markAsRead']);
});
