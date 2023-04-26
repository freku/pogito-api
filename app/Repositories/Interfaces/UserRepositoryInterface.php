<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getByTwitchIdAndName($twitchId, $twitchName): ?User;

    public function getByName(string $name, array $with): ?User;
}
