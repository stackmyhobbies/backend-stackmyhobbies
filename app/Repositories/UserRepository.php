<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function index()
    {
        return User::all();
    }

    public function show($id)
    {

        return User::where('id', $id)->where('status', true)->firstOrFail();
    }

    public function store(array $data)
    {
        return User::create($data);
    }

    public function update(array $data, $id)
    {

        $user = $this->show($id);

        $user->update($data);

        return $user->fresh();
    }

    public function destroy($id)
    {

        $user = $this->show($id);
        return $user->disable();
    }
}