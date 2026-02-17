<?php

namespace App\Service;

use App\Models\Category;

class CategoryService extends Service
{
    protected function modelClass(): string
    {
        return Category::class;
    }
}
