<?php

namespace App\Repositories\Auth;

use App\Interfaces\Auth\RegisterRepositoryInterface;
use App\Models\User;

class RegisterRepository implements RegisterRepositoryInterface
{
    public function store($data): User
    {
        return User::create($data);
    }
}
