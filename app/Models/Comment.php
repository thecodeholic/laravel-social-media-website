<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use function Laravel\Prompts\select;

class Comment extends Model
{
    use HasFactory;

    public int $numOfComments = 0;
    public array $childComments = [];

    protected $fillable = ['post_id', 'comment', 'user_id', 'parent_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'object');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function isOwner($userId)
    {
        return $this->user_id == $userId;
    }
}
