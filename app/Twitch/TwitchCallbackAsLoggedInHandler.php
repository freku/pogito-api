<?php

namespace App\Twitch;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TwitchCallbackAsLoggedInHandler extends TwitchCallbackHandler
{
    protected function handleLinkedUser(User $linkedUser): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return redirect()->route('profile.view', [$user->name])->withErrors(['To twtich konto jest już połaczone z innym kontem.']);
    }

    protected function handleUnlinkedUser(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->name_tw = $this->twitchUser->name;
        $user->twitch_id = $this->twitchUser->id;
        $user->save();

        Auth::login($user);

        return redirect('/');
    }
}
