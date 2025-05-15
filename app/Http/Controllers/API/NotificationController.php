<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Send Notification - call internally, using MongoDB model
    public function sendNotification(array $data)
    {
        try {
            // Optional: validate notification type
            if (!in_array($data['type'], Notification::notificationTypes())) {
                throw new \InvalidArgumentException('Invalid notification type.');
            }

            $notification = Notification::create([
                'user_id' => $data['user'],
                'message' => $data['message'],
                'type' => $data['type'],
                'seen' => $data['seen'] ?? false,
            ]);

            return $notification;
        } catch (\Exception $e) {
            \Log::error('Error sending notification: ' . $e->getMessage());
            return null;
        }
    }

    // Get all notifications for a specific user
    public function getUserNotifications($userId)
    {
        try {
            $notifications = Notification::where('user_id', $userId)
                                ->orderBy('created_at', 'desc')
                                ->get();

            return response()->json($notifications, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch notifications'], 500);
        }
    }

    // Mark a notification as seen
    public function markAsSeen($id)
    {
        try {
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json(['error' => 'Notification not found'], 404);
            }

            $notification->seen = true;
            $notification->save();

            return response()->json($notification, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to mark notification as seen'], 500);
        }
    }

    // Delete a notification
    public function deleteNotification($id)
    {
        try {
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json(['error' => 'Notification not found'], 404);
            }

            $notification->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete notification'], 500);
        }
    }
}
