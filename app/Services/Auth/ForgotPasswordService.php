<?php

namespace App\Services\Auth;

use App\Jobs\SendPasswordResetLinkJob;

class ForgotPasswordService
{
    public function sendResetLink(string $email): void
    {
        SendPasswordResetLinkJob::dispatch($email);
    }
}
