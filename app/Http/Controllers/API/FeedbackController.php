<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path="/api/feedback/create",
 *     summary="Create a new feedback",
 *     tags={"Feedback"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "content", "rating"},
 *             @OA\Property(property="title", type="string", example="Great service!"),
 *             @OA\Property(property="content", type="string", example="The doctor was very professional and helpful."),
 *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Feedback created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Feedback")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/feedback/user/{userId}",
 *     summary="Get all feedbacks for a user",
 *     tags={"Feedback"},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user's feedbacks",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Feedback")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/feedback/{feedbackId}",
 *     summary="Get feedback by ID",
 *     tags={"Feedback"},
 *     @OA\Parameter(
 *         name="feedbackId",
 *         in="path",
 *         required=true,
 *         description="Feedback ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Feedback details",
 *         @OA\JsonContent(ref="#/components/schemas/Feedback")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Feedback not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/feedback/status/{feedbackId}",
 *     summary="Update feedback status",
 *     tags={"Feedback"},
 *     @OA\Parameter(
 *         name="feedbackId",
 *         in="path",
 *         required=true,
 *         description="Feedback ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"status"},
 *             @OA\Property(property="status", type="string", enum={"pending", "reviewed", "resolved"}, example="reviewed"),
 *             @OA\Property(property="response", type="string", example="Thank you for your feedback!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Feedback status updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Feedback")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Feedback not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/feedback/delete/{feedbackId}",
 *     summary="Delete a feedback",
 *     tags={"Feedback"},
 *     @OA\Parameter(
 *         name="feedbackId",
 *         in="path",
 *         required=true,
 *         description="Feedback ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Feedback deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Feedback deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Feedback not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 */
class FeedbackController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $feedback = Feedback::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'rating' => $request->rating,
            'status' => 'pending'
        ]);

        return response()->json($feedback, 201);
    }

    public function getUserFeedbacks($userId)
    {
        $feedbacks = Feedback::where('user_id', $userId)
            ->with('user')
            ->latest()
            ->get();

        return response()->json($feedbacks);
    }

    public function getFeedback($feedbackId)
    {
        $feedback = Feedback::with('user')
            ->findOrFail($feedbackId);

        return response()->json($feedback);
    }

    public function updateStatus(Request $request, $feedbackId)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
            'response' => 'nullable|string'
        ]);

        $feedback = Feedback::findOrFail($feedbackId);
        $feedback->update($request->all());

        return response()->json($feedback);
    }

    public function delete($feedbackId)
    {
        $feedback = Feedback::findOrFail($feedbackId);
        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted successfully']);
    }
} 