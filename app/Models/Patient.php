<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'blood_group',
        'allergies',
        'medical_history',
        'emergency_contact_name',
        'emergency_contact_phone'
    ];

    protected $casts = [
        'date_of_birth' => 'date'
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

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
