<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

 
    protected $connection = 'mongodb';

    protected $collection = 'patients';

    protected $casts = [
        'emergencyContact' => 'array',
        'insurance' => 'array',
        'weight' => 'float',
        'height' => 'float',
    ];

    protected $attributes = [
        'emergencyContact' => [
            'name' => null,
            'relation' => null,
            'phone' => null,
        ],
        'insurance' => [
            'provider' => null,
            'policyNumber' => null,
        ],
    ];

    public $timestamps = true;

    
    public function user()
    {
        return $this->belongsTo(User::class, '_id', 'user_id');
    }
}
