<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Chat;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\API\NotificationController;

class ChatController extends Controller
{
    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    // Create a new chat between two users
    public function createChat(Request $request)
    {
        $participants = $request->input('participants');

        if (!$participants || count($participants) !== 2) {
            throw new BadRequestException('Exactly two participants are required to create a chat.');
        }

        // Check if chat already exists
        $existingChat = Chat::where('participants', 'all', $participants)
                            ->whereRaw(['participants' => ['$size' => 2]])
                            ->first();

        if ($existingChat) {
            return response()->json([
                'message' => 'Chat already exists',
                'chat' => $existingChat
            ], 200);
        }

        $chat = Chat::create([
            'participants' => $participants,
            'messages' => [],
            'lastUpdated' => now(),
        ]);

        // Notify both users
        foreach ($participants as $user) {
            $this->notificationController->sendNotification([
                'user' => $user,
                'message' => 'You have a new message chat.',
                'type' => 'chat',
            ]);
        }

        return response()->json([
            'message' => 'Chat created',
            'chat' => $chat
        ], 201);
    }

    // Send a message in a chat
    public function sendMessage(Request $request, $chatId)
    {
        $chat = Chat::find($chatId);
        if (!$chat) throw new NotFoundException('Chat not found');

        $sender = $request->input('sender');
        $messageText = $request->input('message');

        if (!$sender || !$messageText) {
            throw new BadRequestException('Sender and message text are required.');
        }

        $newMessage = [
            'sender' => $sender,
            'message' => $messageText,
            'sentAt' => now(),
            'isRead' => false,
        ];

        $messages = $chat->messages ?? [];
        $messages[] = $newMessage;

        $chat->messages = $messages;
        $chat->lastUpdated = now();
        $chat->save();

        return response()->json([
            'message' => 'Message sent',
            'chat' => $chat
        ], 201);
    }

    // Get chat by ID
    public function getChatById($chatId)
    {
        $chat = Chat::find($chatId);

        if (!$chat) throw new NotFoundException('Chat not found');

        return response()->json([
            'chat' => $chat
        ], 200);
    }

    // Mark all messages as read for a user
    public function markMessagesAsRead(Request $request, $chatId)
    {
        $userId = $request->input('userId');
        $chat = Chat::find($chatId);

        if (!$chat) throw new NotFoundException('Chat not found');

        $chat->messages = collect($chat->messages)->map(function ($msg) use ($userId) {
            if ((string) $msg['sender'] !== (string) $userId) {
                $msg['isRead'] = true;
            }
            return $msg;
        })->toArray();

        $chat->save();

        return response()->json([
            'message' => 'Messages marked as read',
            'chat' => $chat
        ], 200);
    }

    // Get all chats for a specific user
    public function getUserChats($userId)
    {
        $chats = Chat::where('participants', 'all', [(string) $userId])
                     ->orderBy('lastUpdated', 'desc')
                     ->get();

        return response()->json([
            'chats' => $chats
        ], 200);
    }
}
