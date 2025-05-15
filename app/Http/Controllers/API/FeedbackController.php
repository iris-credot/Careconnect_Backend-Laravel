<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Exceptions\NotFoundException;
use App\Exceptions\BadRequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;

class FeedbackController extends Controller
{
    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    // Create new feedback
    public function createFeedback(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|string',
            'receiver_id' => 'required|string',
            'feedback_text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $feedback = Feedback::create([
            'sender_id' => $validated['sender_id'],
            'receiver_id' => $validated['receiver_id'],
            'feedback_text' => $validated['feedback_text'],
            'rating' => $validated['rating'],
        ]);

        // Notify receiver
        $this->notificationController->sendNotification([
            'user' => $validated['receiver_id'],
            'message' => "You have received new feedback from {$validated['sender_id']}",
            'type' => 'feedback'
        ]);

        // Notify sender
        $this->notificationController->sendNotification([
            'user' => $validated['sender_id'],
            'message' => 'Your feedback was successfully submitted.',
            'type' => 'feedback'
        ]);

        return response()->json([
            'message' => 'Feedback created successfully',
            'feedback' => $feedback
        ], 201);
    }

    // Get all feedback for a particular user
    public function getFeedbackByUser($userId)
    {
        $feedbacks = Feedback::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get();

        if ($feedbacks->isEmpty()) {
            throw new NotFoundException('No feedback found for this user.');
        }

        return response()->json(['feedbacks' => $feedbacks], 200);
    }

    // Get feedback by feedback ID
    public function getFeedbackById($feedbackId)
    {
        $feedback = Feedback::find($feedbackId);

        if (!$feedback) {
            throw new NotFoundException('Feedback not found.');
        }

        return response()->json(['feedback' => $feedback], 200);
    }

    // Update the feedback status
    public function updateFeedbackStatus(Request $request, $feedbackId)
    {
        $status = $request->input('status');

        if (!in_array($status, ['pending', 'resolved'])) {
            throw new BadRequestException('Invalid status.');
        }

        $feedback = Feedback::find($feedbackId);
        if (!$feedback) {
            throw new NotFoundException('Feedback not found.');
        }

        $feedback->status = $status;
        $feedback->save();

        return response()->json([
            'message' => 'Feedback status updated successfully',
            'feedback' => $feedback
        ], 200);
    }

    // Delete feedback
    public function deleteFeedback($feedbackId)
    {
        $feedback = Feedback::find($feedbackId);

        if (!$feedback) {
            throw new NotFoundException('Feedback not found.');
        }

        $feedback->delete();

        return response()->json([
            'message' => 'Feedback deleted successfully',
            'feedback' => $feedback
        ], 200);
    }
}
