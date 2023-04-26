<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'twitch_id', 'name_tw', 'avatar_tw',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class);
    }

    // public function given_bans(): HasMany
    public function givenBans(): HasMany
    {
        return $this->hasMany(Ban::class, 'id', 'by_user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function rank(): HasOne
    {
        return $this->hasOne(Rank::class);
    }

    public function hasPassword(): bool
    {
        return mb_strlen($this->password) > 0;
    }

    public function hasLinkedTwitch(): bool
    {
        return $this->twitch_id !== null && $this->name_tw !== null;
    }
}
