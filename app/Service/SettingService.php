<?php

namespace App\Service;

use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;

class SettingService extends Service
{
    protected function modelClass(): string
    {
        return UserSetting::class;
    }

    public function show($id = null)
    {
        // $id is ignored here because we only care about the logged in user
        $user_id = auth()->id();
        $setting = UserSetting::firstOrCreate(
            ['user_id' => $user_id],
            [
                'monthly_income' => 0,
                'monthly_savings_goal' => 0,
                'savings_percentage' => 0,
                'notes' => ''
            ]
        );

        return $this->success($setting);
    }

    public function update($id, array $data)
    {
        $user_id = auth()->id();
        $setting = UserSetting::firstOrCreate(
            ['user_id' => $user_id],
            [
                'monthly_income' => 0,
                'monthly_savings_goal' => 0,
                'savings_percentage' => 0,
                'notes' => ''
            ]
        );

        $setting->update([
            'monthly_income' => $data['monthly_income'] ?? 0,
            'monthly_savings_goal' => $data['monthly_savings_goal'] ?? 0,
            'savings_percentage' => $data['savings_percentage'] ?? 0,
            'notes' => $data['notes'] ?? '',
        ]);

        return $this->success($setting, 'Configurações salvas com sucesso');
    }
}
