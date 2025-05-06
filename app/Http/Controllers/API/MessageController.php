<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $messages = $user->sentMessages()
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string'],
        ]);

        $user = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        // Check if the sender and receiver are allowed to communicate
        if (!$this->canCommunicate($user, $receiver)) {
            return response()->json(['message' => 'You are not allowed to send messages to this user'], 403);
        }

        $message = $user->sentMessages()->create([
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver'])
        ], 201);
    }

    public function show(Message $message)
    {
        $user = Auth::user();
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($message->load(['sender', 'receiver']));
    }

    public function update(Request $request, Message $message)
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        $user = Auth::user();
        if ($message->sender_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->update(['message' => $request->message]);

        return response()->json([
            'message' => 'Message updated successfully',
            'data' => $message->load(['sender', 'receiver'])
        ]);
    }

    public function destroy(Message $message)
    {
        $user = Auth::user();
        if ($message->sender_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->delete();
        return response()->json(['message' => 'Message deleted successfully']);
    }

    public function conversation(User $user)
    {
        $currentUser = Auth::user();
        if (!$this->canCommunicate($currentUser, $user)) {
            return response()->json(['message' => 'You are not allowed to view this conversation'], 403);
        }

        $messages = Message::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $currentUser->id);
        })
        ->with(['sender', 'receiver'])
        ->latest()
        ->get();

        return response()->json($messages);
    }

    public function markAsRead(Message $message)
    {
        $user = Auth::user();
        if ($message->receiver_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'message' => 'Message marked as read',
            'data' => $message->load(['sender', 'receiver'])
        ]);
    }

    private function canCommunicate($user1, $user2)
    {
        // Doctors can communicate with their patients
        if ($user1->isDoctor() && $user2->isPatient()) {
            return true;
        }

        // Patients can communicate with their doctors
        if ($user1->isPatient() && $user2->isDoctor()) {
            return true;
        }

        // Admins can communicate with everyone
        if ($user1->isAdmin() || $user2->isAdmin()) {
            return true;
        }

        return false;
    }
}
