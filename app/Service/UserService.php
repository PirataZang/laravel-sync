<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
class UserService extends Service
{
    protected function modelClass(): string
    {
        return User::class;
    }
    
    public function create(array $data)
    {
        if (!empty($data['password']))
            $data['password'] = Hash::make($data['password']);


        return parent::create($data);
    }

    public function update(int|string $id, array $data)
    {
        if (isset($data['password'])) {

            // se senha vier vazia → não atualiza
            if (empty($data['password']))
                unset($data['password']);
            else
                $data['password'] = Hash::make($data['password']);

        }

        return parent::update($id, $data);
    }

    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials))
            return $this->error('Credenciais inválidas', 401);

        $user = Auth::user();

        // gera token seguro
        $token = hash('sha256', Str::random(60));

        // expira em 7 dias
        $expiresAt = Carbon::now()->addDays(7);

        // salva no banco
        $user->update([
            'token' => $token,
            'token_expires_at' => $expiresAt,
        ]);

        // salva na sessão
        session([
            'auth_user_id' => $user->id,
            'auth_token' => $token,
            'auth_expires_at' => $expiresAt,
        ]);

        return $this->success([
            'user' => $user,
            'token' => $token,
            'expires_at' => $expiresAt,
        ], 'Login realizado com sucesso');
    }

    public function logout()
    {
        $user = Auth::user();

        if (!$user) {
            return $this->error('Usuário não autenticado', 401);
        }

        $user->update([
            'token' => null,
            'token_expires_at' => null,
        ]);

        Auth::forgetUser();

        return $this->success([], 'Logout realizado com sucesso');
    }


}
