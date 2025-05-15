<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SportRecommendation extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';      // Using MongoDB connection
    protected $collection = 'sport_recommendations';  // Optional: specify collection name

    // Mass assignable attributes
    protected $fillable = [
        'patient_id',
        'recommended_sports',
        'notes',
    ];

    // Cast recommended_sports as array so it's handled properly
    protected $casts = [
        'recommended_sports' => 'array',
    ];

    // Automatically handle created_at and updated_at timestamps
    public $timestamps = true;

    // Define relation to Patient model
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
