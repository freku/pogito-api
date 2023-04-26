<?php

namespace App\Http\Controllers;

use App\Http\Requests\FirstPasswordRequest;
use App\Http\Requests\UpdateProfilePictureRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\TwitchServiceInterface;
use App\View\Models\ProfileDataModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository, private TwitchServiceInterface $twitchService)
    {
    }

    public function view(Request $request): View
    {
        $user = $this->userRepository->getByName($request->name, ['posts', 'comments', 'likes']);

        return view('profile.view', $user ? ProfileDataModel::create($user, $request->name) : []);
    }

    public function settings(): View
    {
        /** @var User $user */
        $user = auth()->user();

        return view('profile.settings', [
            'user' => $user->name,
            'is_pw_set' => $user->hasPassword(),
            'is_twitch_connected' => $user->hasLinkedTwitch(),
        ]);
    }

    // TODO: should it check if user has password set?
    public function setFirstPassword(FirstPasswordRequest $request): RedirectResponse
    {
        /** @var App\Models\User $user */
        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::back();
    }

    // TODO: add password confirm before unlinking (more secure)
    public function unlinkTwitchAccount(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($request->acc_name != Auth::user()->name) {
            return Redirect::back()->withErrors(['wrong_nick' => 'Podany nick nie jest poprawny. Twój nick to: '.Auth::user()->name.'.']);
        }

        if (! $user->hasPassword()) {
            // TODO: check if there is reason why i did it this way, if not, just remove the user
            // nie chce usuwac postow usera wiec albo softdelete albo zostawic tak jak jest i dawac zamiast pustego nicka [deleted] czy cos
            // $user->delete();
            $user->name_tw = '';
            $user->twitch_id = '';
            $user->email = 'KEK-'.$user->name;
            $user->name = '.';
            $user->password = '';
            $user->save();

            Auth::logout();

            return redirect(RouteServiceProvider::HOME);
        } else {
            $user->name_tw = null;
            $user->twitch_id = null;
            $user->save();

            return Redirect::back();
        }
    }

    public function setProfilePictureFromTwitch(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasLinkedTwitch()) {
            return Redirect::back()->withErrors(['avatar' => 'Konto twitch nie jest połączone.']);
        }

        // TODO: cache access token
        $at = $this->twitchService->getAccessToken();

        $user->avatar = $this->twitchService->getProfileImageUrl($at, $user->name_tw);
        $user->save();

        return Redirect::back()->withErrors(['success' => 'Pomyślnie zmieniono avatar profilowy.']);
    }

    public function updateProfilePicture(UpdateProfilePictureRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $image = Image::make($request->file('avatar')->getRealPath())->fit(300, 300);

        $name = $user->name.'_'.$user->id.'.'.$request->file('avatar')->getClientOriginalExtension();

        $folder = '/images/avatars/';
        $path = Storage::disk('public')->path($folder.$name);

        $image->save($path);
        $user->update(['avatar' => URL('storage'.$folder.$name)]);

        return Redirect::back()->withErrors(['success' => 'Pomyślnie zmieniono avatar profilowy.']);
    }
}
