<?php

namespace Kolette\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerificationTokenMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var \Kolette\Auth\Models\User $user */
        $user = auth()->user();

        if (!$user) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $verificationToken = $request->header('Verification-Token', $request->query('_verification_token'));

        if (!$verificationToken) {
            throw new HttpException(Response::HTTP_FORBIDDEN, __('auth::error_messages.verification_token.required'));
        }

        if (!$user->hasValidVerificationToken($verificationToken)) {
            throw new HttpException(Response::HTTP_FORBIDDEN, __('auth::error_messages.verification_token.invalid'));
        }

        return $next($request);
    }
}
