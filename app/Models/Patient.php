<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weight',
        'height',
        'emergencyContact',
        'insurance',
    ];

    protected $casts = [
        'emergencyContact' => 'array',
        'insurance' => 'array',
        'weight' => 'float',
        'height' => 'float',
    ];

    protected $attributes = [
        'insurance' => '{"provider": null, "policyNumber": null}',
        'emergencyContact' => '{"name": null, "relation": null, "phone": null}',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // fixed for SQL
    }
}
