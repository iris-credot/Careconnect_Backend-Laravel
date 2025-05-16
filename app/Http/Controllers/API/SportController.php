<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\NotificationController;
use Symfony\Component\HttpFoundation\Response;

class SportRecommendationController extends Controller
{
    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    // Create a new sport recommendation
    public function createSportRecommendation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|string',
            'recommended_sports' => 'required|array|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Validation failed: ' . $validator->errors()->first());
        }

        $sportRecommendation = SportRecommendation::create([
            'patient_id' => $request->patient_id,
            'recommended_sports' => $request->recommended_sports,
            'notes' => $request->notes,
        ]);

        // Send notification to patient
        $this->notificationController->sendNotification([
            'user' => $request->patient_id,
            'message' => 'You have received a new sport recommendation.',
            'type' => 'sport',
        ]);

        return response()->json([
            'message' => 'Sport recommendation created successfully',
            'sportRecommendation' => $sportRecommendation,
        ], Response::HTTP_CREATED);
    }

    // Get all sport recommendations
    public function getAllSportRecommendations()
    {
        $recommendations = SportRecommendation::with('patient')->get();

        return response()->json(['recommendations' => $recommendations], 200);
    }

    // Get sport recommendation by ID
    public function getSportRecommendationById($id)
    {
        $recommendation = SportRecommendation::with('patient')->find($id);

        if (!$recommendation) {
            throw new NotFoundException("No sport recommendation found with ID $id");
        }

        return response()->json(['recommendation' => $recommendation], 200);
    }

    // Update a sport recommendation
    public function updateSportRecommendation(Request $request, $id)
    {
        $recommendation = SportRecommendation::find($id);

        if (!$recommendation) {
            throw new NotFoundException("No sport recommendation found with ID $id");
        }

        $validator = Validator::make($request->all(), [
            'recommended_sports' => 'nullable|array|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Validation failed: ' . $validator->errors()->first());
        }

        $recommendation->update($request->all());

        return response()->json([
            'message' => 'Sport recommendation updated successfully',
            'sportRecommendation' => $recommendation,
        ], 200);
    }

    // Delete a sport recommendation
    public function deleteSportRecommendation($id)
    {
        $recommendation = SportRecommendation::find($id);

        if (!$recommendation) {
            throw new NotFoundException("No sport recommendation found with ID $id");
        }

        $recommendation->delete();

        return response()->json([
            'message' => 'Sport recommendation deleted successfully',
        ], 200);
    }
}
