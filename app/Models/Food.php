<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodRecommendation extends Model
{
    use HasFactory;

    protected $connection = 'mongodb'; // MongoDB connection
    protected $collection = 'food_recommendations'; // MongoDB collection name

    protected $fillable = [
        'patient_id',
        'recommended_foods',
        'notes',
    ];

    // Cast the recommended_foods field as an array to handle the sub-documents
    protected $casts = [
        'recommended_foods' => 'array',
    ];

    public $timestamps = true;

    // Relationship to Patient model
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
