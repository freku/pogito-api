<?php

namespace App\Twitch;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TwitchCallbackAsGuestHandler extends TwitchCallbackHandler
{
    protected function handleLinkedUser(User $linkedUser): RedirectResponse
    {
        Auth::login($linkedUser);

        return redirect('/');
    }

    protected function handleUnlinkedUser(): RedirectResponse
    {
        $isEmailTaken = User::where('email', $this->twitchUser->email)->exists();

        $data = [
            'name' => $this->twitchUser->name,
            'id' => $this->twitchUser->id,
            'avatar' => $this->twitchUser->avatar,
            'email' => $isEmailTaken ? null : $this->twitchUser->email,
        ];

        session(['twitch_cb_user' => $data]);

        return redirect()->route('register.by-twitch');
    }
}
