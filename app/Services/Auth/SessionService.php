<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\SessionRepositoryInterface;
use App\Models\User;
use App\Support\TryCatch;
use Illuminate\Auth\AuthenticationException;

use Illuminate\Support\Facades\Hash;
use PhpParser\Builder\Function_;

class SessionService
{

    public function __construct(protected SessionRepositoryInterface $sessionRepository) {}

    public function store(array $credentials): array
    {

        return TryCatch::handle(
            function () use ($credentials) {
                $user = $this->sessionRepository->findByEmailOrUsername($credentials['login']);
                if (! $user || ! Hash::check($credentials['password'], $user->password)) {
                    throw new AuthenticationException('Credenciales inválidas.');
                }

                $token = $user->createToken('API Token')->plainTextToken;

                return [
                    'user' => $user,
                    'token' => $token,
                ];
            }
        );
    }


    public function destroy(User $user)
    {
        return TryCatch::handle(
            function () use ($user) {
                /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
                $token = $user->currentAccessToken();

                if ($token) {
                    $token->delete();
                    return true;
                }

                return false;
            }
        );
    }
}
