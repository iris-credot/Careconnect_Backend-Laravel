<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'reportDate',
        'summary',
        'vitals',
        'diagnosis',
        'allergies',
        'medications',
        'lifestyleRecommendations',
        'nextAppointment',
    ];

    protected $casts = [
        'reportDate' => 'datetime',
        'nextAppointment' => 'datetime',
        'vitals' => 'array',
        'allergies' => 'array',
        'medications' => 'array',
    ];

    public $timestamps = true;

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
