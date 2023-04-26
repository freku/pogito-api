<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'likes' => 0,
        // TODO: check if it can be done with soft deletes instead
        'is_removed' => 0,
    ];

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // public function sub_coms(): HasMany
    public function childrenComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'sub_of');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'sub_of', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
