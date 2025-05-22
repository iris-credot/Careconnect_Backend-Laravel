<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract
{
    use HasFactory, Notifiable, Authenticatable;

    protected $fillable = [
        'username',
        'firstName',
        'lastName',
        'names',
        'bio',
        'role',
        'address',
        'phoneNumber',
        'dateOfBirth',
        'email',
        'gender',
        'password',
        'otpExpires',
        'otp',
        'verified',
    ];

    protected $hidden = [
        'password',
        'otp',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'dateOfBirth' => 'date',
    ];

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public static function validationRules()
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ];
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class); // defaults to user_id
    }

    public function patient()
    {
        return $this->hasOne(Patient::class); // defaults to user_id
    }
}
