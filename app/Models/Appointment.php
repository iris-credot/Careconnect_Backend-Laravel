<?php

namespace App\Models;

/**
 * @OA\Schema(
 *     schema="Appointment",
 *     required={"doctor_id", "patient_id", "appointment_date", "status"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="doctor_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="patient_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="appointment_date", type="string", format="date-time", example="2024-03-20 10:00:00"),
 *     @OA\Property(property="time_slot", type="string", example="10:00 AM"),
 *     @OA\Property(property="status", type="string", enum={"scheduled", "completed", "cancelled", "rescheduled"}, example="scheduled"),
 *     @OA\Property(property="type", type="string", enum={"regular", "followup", "emergency"}, example="regular"),
 *     @OA\Property(property="reason", type="string", nullable=true, example="Regular checkup"),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Patient has history of hypertension"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'status',
        'reason',
        'notes'
    ];

    protected $casts = [
        'appointment_date' => 'datetime'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }
}
