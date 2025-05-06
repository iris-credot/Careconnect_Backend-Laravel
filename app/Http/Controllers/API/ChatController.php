<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path="/api/chat/create",
 *     summary="Create a new chat",
 *     tags={"Chats"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"recipient_id"},
 *             @OA\Property(property="recipient_id", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Chat created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Chat")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/api/chat/message/{chatId}",
 *     summary="Create a new message in a chat",
 *     tags={"Chats"},
 *     @OA\Parameter(
 *         name="chatId",
 *         in="path",
 *         required=true,
 *         description="Chat ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"content"},
 *             @OA\Property(property="content", type="string", example="Hello, how are you?")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Message sent successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Message")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Chat not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/chat/{chatId}",
 *     summary="Get chat by ID",
 *     tags={"Chats"},
 *     @OA\Parameter(
 *         name="chatId",
 *         in="path",
 *         required=true,
 *         description="Chat ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Chat details with messages",
 *         @OA\JsonContent(
 *             @OA\Property(property="chat", ref="#/components/schemas/Chat"),
 *             @OA\Property(
 *                 property="messages",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Message")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Chat not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/chat/user/{userId}",
 *     summary="Get all chats for a user",
 *     tags={"Chats"},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user's chats",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Chat")
 *         )
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/chat/read/{chatId}",
 *     summary="Mark all messages as read in a chat",
 *     tags={"Chats"},
 *     @OA\Parameter(
 *         name="chatId",
 *         in="path",
 *         required=true,
 *         description="Chat ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Messages marked as read successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Messages marked as read successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Chat not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 */
class ChatController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id'
        ]);

        $chat = Chat::create([
            'user_id' => Auth::id(),
            'recipient_id' => $request->recipient_id
        ]);

        return response()->json($chat, 201);
    }

    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $chat = Chat::findOrFail($chatId);
        
        $message = $chat->messages()->create([
            'sender_id' => Auth::id(),
            'content' => $request->content
        ]);

        $chat->update([
            'last_message' => $request->content,
            'last_message_time' => now(),
            'unread_count' => $chat->unread_count + 1
        ]);

        return response()->json($message, 201);
    }

    public function getChat($chatId)
    {
        $chat = Chat::with(['messages.sender', 'user', 'recipient'])
            ->findOrFail($chatId);

        return response()->json($chat);
    }

    public function getUserChats($userId)
    {
        $chats = Chat::with(['user', 'recipient'])
            ->where('user_id', $userId)
            ->orWhere('recipient_id', $userId)
            ->get();

        return response()->json($chats);
    }

    public function markAsRead($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        
        $chat->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', Auth::id())
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        $chat->update(['unread_count' => 0]);

        return response()->json(['message' => 'Messages marked as read successfully']);
    }
} 