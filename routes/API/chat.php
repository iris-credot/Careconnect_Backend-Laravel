<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ChatController;

Route::middleware(['jwt:doctor,admin'])->prefix('chat')->group(function () {
    // Create a new chat between two users
    Route::post('/chat/create', [ChatController::class, 'createChat']);

    // Send a message in a chat
    Route::post('/chat/message/{chatId}', [ChatController::class, 'sendMessage']);

    // Get a specific chat by ID
    Route::get('/chat/{chatId}', [ChatController::class, 'getChatById']);

    // Mark messages in a chat as read
    Route::put('/chat/read/{chatId}', [ChatController::class, 'markMessagesAsRead']);

    // Get all chats for a user
    Route::get('/chat/user/{userId}', [ChatController::class, 'getUserChats']);
});
