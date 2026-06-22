<?php

namespace App\Interfaces\Auth;

use App\Models\User;

interface SessionRepositoryInterface
{
    /**
     //* Busca un usuario por email o nombre de usuario.
     *
     //* @return User|null
     */
    public function findByEmailOrUsername(string $login): ?User;
}
