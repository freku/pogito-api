<?php

namespace App\Twitch;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;

abstract class TwitchCallbackHandler
{
    public function __construct(protected $twitchUser, protected UserRepositoryInterface $userRepository)
    {
    }

    public function handleCallback(): RedirectResponse
    {
        $linkedUser = $this->userRepository->getByTwitchIdAndName($this->twitchUser->id, $this->twitchUser->name);

        if ($linkedUser) {
            return $this->handleLinkedUser($linkedUser);
        } else {
            return $this->handleUnlinkedUser();
        }
    }

    abstract protected function handleLinkedUser(User $linkedUser): RedirectResponse;

    abstract protected function handleUnlinkedUser(): RedirectResponse;
}
