<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = auth()->user();
        
        if ($user->mode !== 'seller' || $user->seller_tab !== 'active') {
            return response()->json(['message' => 'Unauthorized. Active seller access required.'], 403);
        }

        return $next($request);
    }
}