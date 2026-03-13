<?php

namespace App\Http\Controllers;

use App\Service\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        return $this->service->show();
    }

    public function update(Request $request)
    {
        return $this->service->update(null, $request->all());
    }
}
