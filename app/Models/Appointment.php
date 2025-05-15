<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model; // MongoDB-compatible base model


class Appointment extends Model
{
    use HasFactory;

    protected $connection = 'mongodb'; // If you're using MongoDB
    protected $collection = 'appointments'; // Optional, specify collection name

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'timeSlot',
        'newDate',
        'newTimeSlot',
        'reason',
        'status',
        'action',
        'notes'
    ];

    protected $casts = [
        'date' => 'datetime',
        'newDate' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

   
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', '_id');
    }

  
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', '_id');
    }
}
