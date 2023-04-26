<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/x/{name}', [ProfileController::class, 'view'])->name('profile.view');

Route::middleware('auth')->group(function () {
    // Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');

    Route::post('/set-first-password', [ProfileController::class, 'setFirstPassword'])->name('profile.set-first-password');
    Route::post('/unlink-twitch-account', [ProfileController::class, 'unlinkTwitchAccount'])->name('profile.unlink-twitch-account');
    Route::post('/pfp-from-twitch', [ProfileController::class, 'setProfilePictureFromTwitch'])->name('profile.pfp-from-twitch');
    Route::post('/update-pfp', [ProfileController::class, 'updateProfilePicture'])->name('profile.update-pfp');
});

// Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::post('/post', [PostController::class, 'store'])->name('posts.store');
// Route::get('/post/{slug}', [PostController::class, 'show'])->name('posts.show');
// Route::get('/post/create', [PostController::class, 'create'])->name('posts.create');

Route::get('/login/twitch', [TwitchController::class, 'redirect'])->name('twitch-login');
Route::get('/login/twitch/cb', [TwitchController::class, 'callback']);

// Route::get('/register/by-twitch', [TwitchController::class, 'newAccountByTwitch'])->name('register.by-twitch');
Route::post('/register/by-twitch', [TwitchController::class, 'registerByTwitch'])->name('register.by-twitch.post');
