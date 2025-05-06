<?php

namespace App\Models;

/**
 * @OA\Schema(
 *     schema="Chat",
 *     required={"user_id", "recipient_id"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="recipient_id", type="integer", format="int64", example=2),
 *     @OA\Property(property="last_message", type="string", nullable=true, example="Hello, how are you?"),
 *     @OA\Property(property="last_message_time", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="unread_count", type="integer", example=0),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_id',
        'last_message',
        'last_message_time',
        'unread_count'
    ];

    protected $casts = [
        'last_message_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
} 