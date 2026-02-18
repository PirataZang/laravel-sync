<?php

namespace App\Http\Controllers;

use App\Service\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
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
}
