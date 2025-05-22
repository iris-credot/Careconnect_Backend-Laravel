<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SportRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'recommended_sports',
        'notes',
    ];

    protected $casts = [
        'recommended_sports' => 'array',
    ];

    public $timestamps = true;

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
