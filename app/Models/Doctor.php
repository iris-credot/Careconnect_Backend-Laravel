<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Doctor",
 *     required={"user_id", "specialization", "license_number"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="specialization", type="string", example="Cardiology"),
 *     @OA\Property(property="license_number", type="string", example="MD123456"),
 *     @OA\Property(property="experience_years", type="integer", example=10),
 *     @OA\Property(property="education", type="string", example="MD, Harvard Medical School"),
 *     @OA\Property(property="hospital_affiliation", type="string", example="General Hospital"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'license_number',
        'experience_years',
        'education',
        'hospital_affiliation'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'consultation_fee' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
