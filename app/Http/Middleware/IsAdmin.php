<?php

namespace App\Http\Middleware;

use App\Classes\ApiResponseClass;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_admin) {
            return ApiResponseClass::sendError('Acceso denegado. Solo administradores.', [], 403);
        }

        return $next($request);
    }
}
