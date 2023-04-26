<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $guarded = [];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    // TODO: refactor it lmao
    public function exists($name)
    {
        if ($this->where('name', '=', $name)->exists()) {
            return true;
        }

        return false;
    }

    public function add($array)
    {
        return User::create([
            'name' => $array['name'],
        ]);
    }
}
