<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    public function getAll();

    public function getById($id);

    public function deleteById($id);
}
