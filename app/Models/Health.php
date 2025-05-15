<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Health extends Model
{
    use HasFactory;

    protected $connection = 'mongodb'; 
    protected $collection = 'healths'; 

    protected $fillable = [
        'patient_id',
        'chronic_diseases',
        'allergies',
        'medications',
        'surgeries',
        'family_history',
        'lifestyle',
        'updatedAt',
    ];

    protected $casts = [
        'chronic_diseases' => 'array', 
        'allergies' => 'array',        
        'medications' => 'array',      
        'family_history' => 'array',   
        'lifestyle' => 'array',        
        'updatedAt' => 'datetime',
    ];

    public $timestamps = true;

  
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
