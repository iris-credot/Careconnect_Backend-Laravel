<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'license_number',
        'experience_years',
        'education',
        'hospital_affiliation',
        'is_available',
        'consultation_fee'
    ];

    protected $casts = [
        'experience_years'     => 'integer',
        'is_available'         => 'boolean',
        'consultation_fee'     => 'float',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'doctor_id');
    }
}
