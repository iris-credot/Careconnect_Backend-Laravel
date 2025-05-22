<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'recommended_foods',
        'notes',
    ];

    protected $casts = [
        'recommended_foods' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
