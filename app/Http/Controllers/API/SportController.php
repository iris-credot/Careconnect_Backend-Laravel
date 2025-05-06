<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/api/sports/create",
 *     summary="Create a new sport recommendation",
 *     tags={"Sports"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description", "benefits", "duration", "intensity"},
 *             @OA\Property(property="name", type="string", example="Running"),
 *             @OA\Property(property="description", type="string", example="Aerobic exercise that improves cardiovascular health"),
 *             @OA\Property(property="benefits", type="string", example="Improves heart health, burns calories, strengthens muscles"),
 *             @OA\Property(property="duration", type="string", example="30 minutes"),
 *             @OA\Property(property="intensity", type="string", enum={"low", "medium", "high"}, example="medium"),
 *             @OA\Property(property="equipment", type="string", nullable=true, example="Running shoes"),
 *             @OA\Property(property="precautions", type="string", nullable=true, example="Warm up properly before starting")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Sport recommendation created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Sport")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/sports/all",
 *     summary="Get all sport recommendations",
 *     tags={"Sports"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all sport recommendations",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Sport")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/sports/get/{id}",
 *     summary="Get a sport recommendation by ID",
 *     tags={"Sports"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Sport ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sport recommendation details retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Sport")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Sport recommendation not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/sports/update/{id}",
 *     summary="Update a sport recommendation",
 *     tags={"Sports"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Sport ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Running"),
 *             @OA\Property(property="description", type="string", example="Updated description"),
 *             @OA\Property(property="benefits", type="string", example="Updated benefits"),
 *             @OA\Property(property="duration", type="string", example="45 minutes"),
 *             @OA\Property(property="intensity", type="string", enum={"low", "medium", "high"}),
 *             @OA\Property(property="equipment", type="string"),
 *             @OA\Property(property="precautions", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sport recommendation updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Sport")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Sport recommendation not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/sports/delete/{id}",
 *     summary="Delete a sport recommendation",
 *     tags={"Sports"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Sport ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sport recommendation deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Sport recommendation deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Sport recommendation not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 */
class SportController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'benefits' => 'required|string',
            'duration' => 'required|string|max:50',
            'intensity' => 'required|in:low,medium,high',
            'equipment' => 'nullable|string',
            'precautions' => 'nullable|string'
        ]);

        $sport = Sport::create($request->all());
        return response()->json($sport, 201);
    }

    public function all()
    {
        $sports = Sport::all();
        return response()->json($sports);
    }

    public function show($id)
    {
        $sport = Sport::findOrFail($id);
        return response()->json($sport);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'benefits' => 'sometimes|string',
            'duration' => 'sometimes|string|max:50',
            'intensity' => 'sometimes|in:low,medium,high',
            'equipment' => 'nullable|string',
            'precautions' => 'nullable|string'
        ]);

        $sport = Sport::findOrFail($id);
        $sport->update($request->all());

        return response()->json($sport);
    }

    public function delete($id)
    {
        $sport = Sport::findOrFail($id);
        $sport->delete();

        return response()->json(['message' => 'Sport recommendation deleted successfully']);
    }
} 