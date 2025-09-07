<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\RegisterRepositoryInterface;
use App\Models\User;
use App\Support\TryCatch;

class RegisterService
{

    public function __construct(private RegisterRepositoryInterface $registerRepository) {}
    public function store(array $data): User
    {

        return TryCatch::handle(
            function () use ($data) {
                return $this->registerRepository->store($data);
            }
        );
    }
}
