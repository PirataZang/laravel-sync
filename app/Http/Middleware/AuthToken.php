<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token não informado'], 401);
        }

        $user = User::where('token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        // opcional: deixar o usuário disponível
        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}
