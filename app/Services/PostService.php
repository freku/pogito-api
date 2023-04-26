<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Support\Carbon;
use App\Services\Interfaces\PostServiceInterface;

class PostService implements PostServiceInterface
{
    public function createPostWithTags(string $title, array $clipData, array $tags): Post
    {
        $popularity = Post::avg('popularity');

        $post = Post::create([
            'user_id' => auth()->user()->id,
            'thumbnail_url' => $clipData['thumbnail_url'],
            'clip_url' => $clipData['embed_url'] . '&parent=' . env('APP_DOMAIN_ONLY'),
            'popularity' => $popularity > 0 ? $popularity : 10,
            'title' => $title,
            'streamer_name' => $clipData['broadcaster_name'],
            'last_activity' => Carbon::now()->toDateTimeString(),
        ]);

        $this->processTags($tags, $post);

        return $post;
    }

    public function processTags(array $tags, Post $post)
    {
        $tagIds = [];

        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }

        $post->tags()->sync($tagIds);
    }

    public function getFormattedTimeDifference(string $created_at): string
    {
        $created_at = Carbon::parse($created_at);
        $now = Carbon::now();

        $dif = $now->diffInHours($created_at);

        if ($dif <= 24) {
            return $dif . ' godz. temu';
        } else {
            return $created_at->format('d.m.Y');
        }
    }
}
