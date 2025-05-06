<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Health;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path="/api/health/create",
 *     summary="Create a Health Application",
 *     tags={"Health"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"patient_id", "health_condition", "allergies", "medications"},
 *             @OA\Property(property="patient_id", type="integer", example=1),
 *             @OA\Property(property="health_condition", type="string", example="Hypertension"),
 *             @OA\Property(property="allergies", type="string", example="Penicillin, Peanuts"),
 *             @OA\Property(property="medications", type="string", example="Lisinopril 10mg daily"),
 *             @OA\Property(property="family_history", type="string", nullable=true, example="Father had heart disease"),
 *             @OA\Property(property="lifestyle", type="string", nullable=true, example="Non-smoker, exercises 3 times a week")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Health application created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Health")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/health/all",
 *     summary="List all Health Applications",
 *     tags={"Health"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all health applications",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Health")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/health/health/{healthId}",
 *     summary="Get Health by patient Id",
 *     tags={"Health"},
 *     @OA\Parameter(
 *         name="healthId",
 *         in="path",
 *         required=true,
 *         description="Health record ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Health record details",
 *         @OA\JsonContent(ref="#/components/schemas/Health")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Health record not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/health/update/{healthId}",
 *     summary="Update Health",
 *     tags={"Health"},
 *     @OA\Parameter(
 *         name="healthId",
 *         in="path",
 *         required=true,
 *         description="Health record ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="health_condition", type="string", example="Hypertension"),
 *             @OA\Property(property="allergies", type="string", example="Penicillin, Peanuts"),
 *             @OA\Property(property="medications", type="string", example="Lisinopril 10mg daily"),
 *             @OA\Property(property="family_history", type="string", nullable=true, example="Father had heart disease"),
 *             @OA\Property(property="lifestyle", type="string", nullable=true, example="Non-smoker, exercises 3 times a week")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Health record updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Health")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Health record not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/health/delete/{healthId}",
 *     summary="Delete a Health Application",
 *     tags={"Health"},
 *     @OA\Parameter(
 *         name="healthId",
 *         in="path",
 *         required=true,
 *         description="Health record ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Health record deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Health record deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Health record not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 */
class HealthController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'health_condition' => 'required|string',
            'allergies' => 'required|string',
            'medications' => 'required|string',
            'family_history' => 'nullable|string',
            'lifestyle' => 'nullable|string'
        ]);

        $health = Health::create($request->all());

        return response()->json($health, 201);
    }

    public function all()
    {
        $healthRecords = Health::with('patient')->get();
        return response()->json($healthRecords);
    }

    public function get($healthId)
    {
        $health = Health::with('patient')->findOrFail($healthId);
        return response()->json($health);
    }

    public function update(Request $request, $healthId)
    {
        $request->validate([
            'health_condition' => 'sometimes|string',
            'allergies' => 'sometimes|string',
            'medications' => 'sometimes|string',
            'family_history' => 'nullable|string',
            'lifestyle' => 'nullable|string'
        ]);

        $health = Health::findOrFail($healthId);
        $health->update($request->all());

        return response()->json($health);
    }

    public function delete($healthId)
    {
        $health = Health::findOrFail($healthId);
        $health->delete();

        return response()->json(['message' => 'Health record deleted successfully']);
    }
} 