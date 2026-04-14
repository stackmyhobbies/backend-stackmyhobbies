<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailVerificationJob;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;


class EmailVerificationController extends Controller
{
    /**
     * Enviar enlace de verificación
     */
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'El correo ya está verificado.'
            ]);
        }

        SendEmailVerificationJob::dispatch($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Correo de verificación enviado correctamente.'
        ]);
    }

    /**
     * Reenviar enlace de verificación (requiere token específico)
     */
    public function resendVerificationEmail(Request $request)
    {
        if (!$request->user()->tokenCan('email:verify:send')) {
            return response()->json([
                'success' => false,
                'message' => 'Token no autorizado para esta acción.'
            ], 403);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'El correo ya está verificado.'
            ]);
        }

        SendEmailVerificationJob::dispatch($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Correo de verificación reenviado correctamente.'
        ]);
    }

    /**
     * Verificar el correo cuando el usuario hace clic en el enlace
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Enlace de verificación inválido.'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'El correo ya fue verificado.'], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(config('app.frontend_url') . 'auth/email-verified'); // Ejemplo de redirección al frontend
    }
}
