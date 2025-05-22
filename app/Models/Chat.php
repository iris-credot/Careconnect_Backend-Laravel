<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'participants',
        'messages',
        'lastUpdated',
    ];

    protected $casts = [
        'lastUpdated' => 'datetime',
        'participants' => 'array',
        'messages' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
            $message['sentAt'] = isset($message['sentAt']) ? Carbon::parse($message['sentAt']) : null;
            return $message;
        });
    }

    // Optional: Load participant users if stored as user IDs
    public function participantUsers()
    {
        return \App\Models\User::whereIn('id', $this->participants)->get();
    }
}
