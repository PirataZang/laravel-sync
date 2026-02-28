<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index($request->all());
    }

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    public function show(string $id)
    {
        return $this->service->show($id);
    }

    public function update(Request $request, string $id)
    {
        return $this->service->update($id, $request->all());
    }

    public function destroy(string $id)
    {
        return $this->service->delete($id);
    }

    public function login(Request $request)
    {
        return $this->service->login($request->all());
    }

    public function logout()
    {
        return $this->service->logout();
    }
}
