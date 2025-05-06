<?php

namespace App\Models;

/**
 * @OA\Schema(
 *     schema="Health",
 *     required={"patient_id", "health_condition", "allergies", "medications"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="patient_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="health_condition", type="string", example="Hypertension"),
 *     @OA\Property(property="allergies", type="string", example="Penicillin, Peanuts"),
 *     @OA\Property(property="medications", type="string", example="Lisinopril 10mg daily"),
 *     @OA\Property(property="family_history", type="string", nullable=true, example="Father had heart disease"),
 *     @OA\Property(property="lifestyle", type="string", nullable=true, example="Non-smoker, exercises 3 times a week"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Health extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'health_condition',
        'allergies',
        'medications',
        'family_history',
        'lifestyle'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
} 