<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendVerificationEmailRequest;
use App\Jobs\SendEmailVerificationJob;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class EmailVerificationController extends Controller
{
    public function resendVerificationEmail(ResendVerificationEmailRequest $request): JsonResponse
    {
        $email = $request->validated('email');

        $user = User::where('email', $email)->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            $user->generateEmailVerificationToken();

            SendEmailVerificationJob::dispatch($user);
        }

        return ApiResponseClass::sendResponse(
            null,
            'Si el correo existe en nuestra base de datos, te enviaremos un enlace de verificación.',
            Response::HTTP_OK
        );
    }

    public function verify($id, $hash): JsonResponse|RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return ApiResponseClass::sendResponse(
                null,
                'El correo ya fue verificado.',
                Response::HTTP_OK
            );
        }

        $expectedHash = $user->email_verification_token ?? sha1($user->getEmailForVerification());

        if (! hash_equals($expectedHash, (string) $hash)) {
            return response()->json(['message' => 'Enlace de verificación inválido.'], 400);
        }

        if ($user->markEmailAsVerified()) {
            $user->clearEmailVerificationToken();

            event(new Verified($user));
        }

        return redirect(rtrim(config('app.frontend_url'), '/').'/auth/email-verified');
    }
}
