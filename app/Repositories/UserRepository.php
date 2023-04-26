<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getByName(string $name, array $with): ?User
    {
        return $this->model->with($with)->where('name', $name)->first();
    }

    public function getByTwitchIdAndName($twitchId, $twitchName): ?User
    {
        return $this->model->where('twitch_id', $twitchId)->where('name_tw', $twitchName)->first();
    }
}
