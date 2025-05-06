<?php

namespace App\Models;

/**
 * @OA\Schema(
 *     schema="Feedback",
 *     required={"user_id", "title", "content", "rating"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="title", type="string", example="Great service!"),
 *     @OA\Property(property="content", type="string", example="The doctor was very professional and helpful."),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *     @OA\Property(property="status", type="string", enum={"pending", "reviewed", "resolved"}, example="pending"),
 *     @OA\Property(property="response", type="string", nullable=true, example="Thank you for your feedback!"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'rating',
        'status',
        'response'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 