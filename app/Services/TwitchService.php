<?php

namespace App\Services;

use App\Services\Interfaces\TwitchServiceInterface;
use Illuminate\Support\Facades\Http;

class TwitchService implements TwitchServiceInterface
{
    public function getAccessToken(): string
    {
        $response = Http::post('https://id.twitch.tv/oauth2/token', [
            'client_id' => env('TWITCH_CLIENT_ID'),
            'client_secret' => env('TWITCH_CLIENT_SECRET'),
            'grant_type' => 'client_credentials',
        ]);

        return $response->json('access_token');
    }

    public function getUserData(string $accessToken, string $userName): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Client-Id' => env('TWITCH_CLIENT_ID'),
        ])->get("https://api.twitch.tv/helix/users?login=$userName");

        return $response->json();
    }

    public function getProfileImageUrl(string $accessToken, string $userName): string
    {
        return $this->getUserData($accessToken, $userName)['data'][0]['profile_image_url'];
    }

    public function getClipJsonById(string $accessToken, string $id)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Client-Id' => env('TWITCH_CLIENT_ID'),
        ])->get('https://api.twitch.tv/helix/clips', [
            'id' => $id,
        ]);

        return $response->json();
    }

    public function extractTwitchClipId($url): ?string
    {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?clips\.twitch\.tv\/([a-zA-Z0-9_-]+)/';
        preg_match($pattern, $url, $matches);

        return $matches[1] ?? null;
    }
}
