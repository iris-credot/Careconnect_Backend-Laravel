<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/api/foods/create",
 *     summary="Create a new food recommendation",
 *     tags={"Foods"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description", "nutritional_value", "benefits"},
 *             @OA\Property(property="name", type="string", example="Salmon"),
 *             @OA\Property(property="description", type="string", example="Rich in omega-3 fatty acids and protein"),
 *             @OA\Property(property="nutritional_value", type="string", example="High in protein, omega-3, vitamin D"),
 *             @OA\Property(property="benefits", type="string", example="Supports heart health, brain function, and muscle growth"),
 *             @OA\Property(property="calories", type="integer", example=208),
 *             @OA\Property(property="protein", type="number", format="float", example=22.0),
 *             @OA\Property(property="carbs", type="number", format="float", example=0.0),
 *             @OA\Property(property="fat", type="number", format="float", example=13.0),
 *             @OA\Property(property="serving_size", type="string", example="100g"),
 *             @OA\Property(property="preparation", type="string", nullable=true, example="Grill or bake with herbs")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Food recommendation created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Food")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/foods/all",
 *     summary="Get all food recommendations",
 *     tags={"Foods"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all food recommendations",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Food")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/foods/get/{id}",
 *     summary="Get a food recommendation by ID",
 *     tags={"Foods"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Food ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Food recommendation details retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Food")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Food recommendation not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/foods/update/{id}",
 *     summary="Update a food recommendation",
 *     tags={"Foods"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Food ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Salmon"),
 *             @OA\Property(property="description", type="string", example="Updated description"),
 *             @OA\Property(property="nutritional_value", type="string", example="Updated nutritional value"),
 *             @OA\Property(property="benefits", type="string", example="Updated benefits"),
 *             @OA\Property(property="calories", type="integer"),
 *             @OA\Property(property="protein", type="number", format="float"),
 *             @OA\Property(property="carbs", type="number", format="float"),
 *             @OA\Property(property="fat", type="number", format="float"),
 *             @OA\Property(property="serving_size", type="string"),
 *             @OA\Property(property="preparation", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Food recommendation updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Food")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Food recommendation not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/foods/delete/{id}",
 *     summary="Delete a food recommendation",
 *     tags={"Foods"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Food ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Food recommendation deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Food recommendation deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Food recommendation not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 */
class FoodController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'nutritional_value' => 'required|string',
            'benefits' => 'required|string',
            'calories' => 'required|integer|min:0',
            'protein' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
            'serving_size' => 'required|string|max:50',
            'preparation' => 'nullable|string'
        ]);

        $food = Food::create($request->all());
        return response()->json($food, 201);
    }

    public function all()
    {
        $foods = Food::all();
        return response()->json($foods);
    }

    public function show($id)
    {
        $food = Food::findOrFail($id);
        return response()->json($food);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'nutritional_value' => 'sometimes|string',
            'benefits' => 'sometimes|string',
            'calories' => 'sometimes|integer|min:0',
            'protein' => 'sometimes|numeric|min:0',
            'carbs' => 'sometimes|numeric|min:0',
            'fat' => 'sometimes|numeric|min:0',
            'serving_size' => 'sometimes|string|max:50',
            'preparation' => 'nullable|string'
        ]);

        $food = Food::findOrFail($id);
        $food->update($request->all());

        return response()->json($food);
    }

    public function delete($id)
    {
        $food = Food::findOrFail($id);
        $food->delete();

        return response()->json(['message' => 'Food recommendation deleted successfully']);
    }
} 