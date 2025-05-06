<?php

namespace App\Models;

/**
 * @OA\Schema(
 *     schema="Message",
 *     required={"chat_id", "sender_id", "content"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="chat_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="sender_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="content", type="string", example="Hello, how are you?"),
 *     @OA\Property(property="is_read", type="boolean", example=false),
 *     @OA\Property(property="read_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'content',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
