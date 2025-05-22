<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

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

    public $timestamps = true;

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', '_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', '_id');
    }

    public static function statuses()
    {
        return ['pending', 'approved', 'rescheduled', 'cancelled', 'completed'];
    }
}
