<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otp extends Model
{
    use HasFactory;
    // Collection name (plural)

    protected $fillable = [
        'user_id',
        'token',
        'expirationDate',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'expirationDate' => 'datetime',
    ];

    public $timestamps = true;

  
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
