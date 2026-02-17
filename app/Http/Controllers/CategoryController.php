<?php

namespace App\Http\Controllers;

use App\Service\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $service;

    public function __construct(CategoryService $service)
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
