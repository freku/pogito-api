<?php

namespace App\Services\Interfaces;

use App\Models\Post;

interface PostServiceInterface {
    public function createPostWithTags(string $title, array $clipData, array $tags): Post;

    public function getFormattedTimeDifference(string $created_at): string;
}
