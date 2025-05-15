<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $connection = 'mongodb'; // MongoDB connection
    protected $collection = 'notifications'; // Optional: define collection name

    protected $fillable = [
        'user_id',
        'message',
        'type',
        'seen',
    ];

    protected $casts = [
        'seen' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public static function notificationTypes()
    {
        return ['appointment', 'report', 'chat', 'reminder', 'feedback', 'foodRecommendation', 'health', 'sport'];
    }
}
