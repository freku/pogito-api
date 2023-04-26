<?php

namespace App\Services\Interfaces;

interface TwitchServiceInterface
{
    public function getAccessToken(): string;

    public function getUserData(string $accessToken, string $userName): array;

    public function getProfileImageUrl(string $accessToken, string $userName): string;

    public function extractTwitchClipId($url): ?string;

    public function getClipJsonById(string $accessToken, string $id);
}
