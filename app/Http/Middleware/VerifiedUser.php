<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->verification_code && !$user->email_verified_at && !$user->phone_verified_at) {
            return redirect()->route('verification.form');
        }

        return $next($request);
    }
}
