<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $connection = 'mongodb'; // MongoDB connection
    protected $collection = 'feedbacks'; // MongoDB collection name

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'feedback_text',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public $timestamps = true;

  
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

   
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
