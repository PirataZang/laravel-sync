<?php

namespace App\Service;

use Illuminate\Database\Eloquent\Model;

abstract class Service
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->modelClass());
    }

    abstract protected function modelClass(): string;

    public function index()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data)
    {
        $item = $this->model->findOrFail($id);
        $item->update($data);

        return $item;
    }

    public function delete(int|string $id)
    {
        $item = $this->model->findOrFail($id);
        return $item->delete();
    }

    public function show(int|string $id)
    {
        return $this->model->findOrFail($id);
    }


    protected function success($data = [], $message = 'OK', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($message = 'Erro', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}
