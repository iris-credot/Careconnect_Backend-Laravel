<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Food",
 *     required={"name", "description", "nutritional_value", "benefits"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Salmon"),
 *     @OA\Property(property="description", type="string", example="Rich in omega-3 fatty acids and protein"),
 *     @OA\Property(property="nutritional_value", type="string", example="High in protein, omega-3, vitamin D"),
 *     @OA\Property(property="benefits", type="string", example="Supports heart health, brain function, and muscle growth"),
 *     @OA\Property(property="calories", type="integer", example=208),
 *     @OA\Property(property="protein", type="number", format="float", example=22.0),
 *     @OA\Property(property="carbs", type="number", format="float", example=0.0),
 *     @OA\Property(property="fat", type="number", format="float", example=13.0),
 *     @OA\Property(property="serving_size", type="string", example="100g"),
 *     @OA\Property(property="preparation", type="string", nullable=true, example="Grill or bake with herbs"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'nutritional_value',
        'benefits',
        'calories',
        'protein',
        'carbs',
        'fat',
        'serving_size',
        'preparation'
    ];

    protected $casts = [
        'calories' => 'integer',
        'protein' => 'float',
        'carbs' => 'float',
        'fat' => 'float'
    ];
} 