<?php

namespace App\Repositories\Auth;

use App\Interfaces\Auth\SessionRepositoryInterface;
use App\Models\User;

class SessionRepository implements SessionRepositoryInterface
{
    protected $active = true;

    public function findByEmailOrUsername(string $login): User|null
    {

        return User::where('status', $this->active)->where('email', $login)
            ->orWhere('username', $login)
            ->first();
    }
}
