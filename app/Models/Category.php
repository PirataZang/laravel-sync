<?php

namespace App\Models;

use App\Models\Enum\CategoryType;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    protected $fillable = [
        "name",
        "user_id",
        "active",
        "type",
        "created_at",
        "updated_at",
    ];

    protected $casts = [
        "active" => "boolean",
        'type' => CategoryType::class,
    ];


}
