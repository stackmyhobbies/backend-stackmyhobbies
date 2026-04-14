<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\RegisterRepositoryInterface;
use App\Jobs\SendEmailVerificationJob;
use App\Models\User;
use App\Support\TryCatch;

class RegisterService
{

    public function __construct(private RegisterRepositoryInterface $registerRepository) {}
    public function store(array $data): User
    {

        return TryCatch::handle(function () use ($data) {


            // Crear el usuario
            $user = $this->registerRepository->store($data);

            if ($user) {
                SendEmailVerificationJob::dispatch($user);
            }

            return $user;
        });
    }
}
