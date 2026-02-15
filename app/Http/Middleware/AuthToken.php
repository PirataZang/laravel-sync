<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token não informado'], 401);
        }

        $user = User::where('token', $token)
            ->where('token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Token inválido ou expirado'], 401);
        }

        // 👇 ESSA É A LINHA CERTA PRA API
        Auth::setUser($user);

        return $next($request);
    }

}
