<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Report",
 *     required={"patient_id", "doctor_id", "title", "content"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="patient_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="doctor_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="title", type="string", example="Medical Examination Report"),
 *     @OA\Property(property="content", type="string", example="Patient shows signs of improvement..."),
 *     @OA\Property(property="diagnosis", type="string", example="Common cold with mild fever"),
 *     @OA\Property(property="recommendations", type="string", example="Rest and take prescribed medications"),
 *     @OA\Property(property="attachments", type="array", @OA\Items(type="string"), example=["xray.jpg", "blood_test.pdf"]),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'title',
        'content',
        'diagnosis',
        'recommendations',
        'attachments'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
} 