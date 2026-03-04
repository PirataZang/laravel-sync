<?php

namespace App\Service;

use App\Traits\SearchableFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

abstract class Service
{
    use SearchableFilter;

    protected Model $model;
    protected $key;

    public function __construct()
    {
        $this->model = app($this->modelClass());
        $this->key = strtolower(class_basename($this->modelClass())) . '_list';
    }

    abstract protected function modelClass(): string;

    public function index($request = [])
    {
        if(empty($request['filters']))
            return $this->applyFilters($request);

        // $items = Cache::remember($this->key, 90, function () use ($request) {
            return $this->applyFilters($request);
        // });
        return $items;
    }

    public function create(array $data)
    {
        Cache::forget($this->key);
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data)
    {
        Cache::forget($this->key);
        $item = $this->model->findOrFail($id);
        $item->update($data);

        return $this->success($item);

    }

    public function delete(int|string $id)
    {
        Cache::forget($this->key);
        $item = $this->model->findOrFail($id);
        return $item->delete();
    }

    public function show(int|string $id)
    {
        $item = $this->model->findOrFail($id);
        return $this->success($item);

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

