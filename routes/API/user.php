<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;

Route::prefix('user')->group(function () {

Route::post('/signup', [UserController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot', [UserController::class, 'forgotPassword']);
Route::post('/verifyotp', [UserController::class, 'verifyOtp']);
Route::post('/resetpassword/{token}', [UserController::class, 'resetPassword']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth');


Route::middleware(['jwt.auth'])->group(function () {
    Route::put('/profile/{id}', [UserController::class, 'updateUser']);
    Route::put('/password', [UserController::class, 'updatePassword']);
});

Route::middleware(['jwt.auth:admin'])->group(function () {
    Route::get('/all', [UserController::class, 'getAllUsers']);
    Route::delete('/delete/{id}', [UserController::class, 'deleteUser']);
});
});
