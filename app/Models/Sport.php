<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Sport",
 *     required={"name", "description", "benefits", "duration", "intensity"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Running"),
 *     @OA\Property(property="description", type="string", example="Aerobic exercise that improves cardiovascular health"),
 *     @OA\Property(property="benefits", type="string", example="Improves heart health, burns calories, strengthens muscles"),
 *     @OA\Property(property="duration", type="string", example="30 minutes"),
 *     @OA\Property(property="intensity", type="string", enum={"low", "medium", "high"}, example="medium"),
 *     @OA\Property(property="equipment", type="string", nullable=true, example="Running shoes"),
 *     @OA\Property(property="precautions", type="string", nullable=true, example="Warm up properly before starting"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Sport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'benefits',
        'duration',
        'intensity',
        'equipment',
        'precautions'
    ];
} 