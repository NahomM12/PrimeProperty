<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'Authorization header with Bearer token is required'], 401);
        }

        try {
            $token = PersonalAccessToken::findToken($request->bearerToken());

            if (!$token) {
                return response()->json(['message' => 'Invalid or expired token'], 401);
            }

            $user = $token->tokenable;
            Auth::login($user);
            $request->merge(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        return $next($request);
    }
}
