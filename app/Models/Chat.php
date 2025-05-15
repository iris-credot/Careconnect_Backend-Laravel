<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'chats';

    protected $fillable = [
        'participants',
        'messages',
        'lastUpdated',
    ];

    protected $casts = [
        'lastUpdated' => 'datetime',
        'participants' => 'array',
        'messages' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($chat) {
            $chat->lastUpdated = now();
        });
    }

    public function getMessagesAttribute($value)
    {
        return collect($value)->map(function ($message) {
            $message['sentAt'] = isset($message['sentAt']) ? \Carbon\Carbon::parse($message['sentAt']) : null;
            return $message;
        });
    }
}
