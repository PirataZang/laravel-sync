<?php

namespace App\Service;

use App\Models\User;


class UserService extends Service
{
    protected function modelClass(): string
    {
        return User::class;
    }

    public function teste($request = null)
    {
        return $this->success($request);
    }


    public function userActives($request = null)
    {
        $users = $this->model->active(false)->get();

        if (! $users)
        return $this->success($users);
        
    }

}