<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return rtrim(config('app.frontend_url', ''), '/')."/auth/reset-password?token={$token}&email={$user->email}";
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            $hash = $notifiable->email_verification_token ?? sha1($notifiable->getEmailForVerification());

            $temporarySignedUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $notifiable->getKey(), 'hash' => $hash]
            );

            return rtrim(config('app.frontend_url', ''), '/').'/auth/verify-email?url='.urlencode($temporarySignedUrl);
        });

        $this->configureRateLimiters();
    }

    protected function configureRateLimiters(): void
    {
        RateLimiter::for('verification-resend', function (Request $request) {
            $key = 'verification-resend:'.($request->ip() ?? 'unknown').':'.($request->input('email') ?? 'unknown');

            return [
                Limit::perMinute(2)->by($key),
                Limit::perHour(5)->by($key),
            ];
        });

        RateLimiter::for('forgot-password', function (Request $request) {
            $key = 'forgot-password:'.($request->ip() ?? 'unknown').':'.($request->input('email') ?? 'unknown');

            return [
                Limit::perMinute(3)->by($key),
                Limit::perHour(10)->by($key),
            ];
        });
    }
}
