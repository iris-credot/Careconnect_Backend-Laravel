<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Models\FoodRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\NotificationController;
use Symfony\Component\HttpFoundation\Response;

class FoodRecommendationController extends Controller
{
    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    // Create a new food recommendation
    public function createFoodRecommendation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|string',
            'recommended_foods' => 'required|array|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Validation failed: ' . $validator->errors()->first());
        }

        $recommendation = FoodRecommendation::create([
            'patient_id' => $request->patient_id,
            'recommended_foods' => $request->recommended_foods,
            'notes' => $request->notes,
        ]);

        // Send notification to the patient
        $this->notificationController->sendNotification([
            'user' => $request->patient_id,
            'message' => 'Your doctor has sent new food recommendations.',
            'type' => 'foodRecommendation',
        ]);

        return response()->json([
            'message' => 'Recommendation created successfully',
            'recommendation' => $recommendation,
        ], Response::HTTP_CREATED);
    }

    // Get all food recommendations
    public function getAllRecommendations()
    {
        $recommendations = FoodRecommendation::with('patient')->get();

        return response()->json(['recommendations' => $recommendations], 200);
    }

    // Get recommendation by ID
    public function getRecommendationById($id)
    {
        $recommendation = FoodRecommendation::with('patient')->find($id);

        if (!$recommendation) {
            throw new NotFoundException("No recommendation found with ID $id");
        }

        return response()->json(['recommendation' => $recommendation], 200);
    }

    // Update a recommendation
    public function updateRecommendation(Request $request, $id)
    {
        $recommendation = FoodRecommendation::find($id);

        if (!$recommendation) {
            throw new NotFoundException("No recommendation found with ID $id");
        }

        $validator = Validator::make($request->all(), [
            'recommended_foods' => 'nullable|array|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Validation failed: ' . $validator->errors()->first());
        }

        $recommendation->update($request->all());

        return response()->json([
            'message' => 'Recommendation updated successfully',
            'recommendation' => $recommendation,
        ], 200);
    }

    // Delete a recommendation
    public function deleteRecommendation($id)
    {
        $recommendation = FoodRecommendation::find($id);

        if (!$recommendation) {
            throw new NotFoundException("No recommendation found with ID $id");
        }

        $recommendation->delete();

        return response()->json(['message' => 'Recommendation deleted successfully'], 200);
    }
}
