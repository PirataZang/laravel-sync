<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    protected $fillable = [
        "name",
        "user_id",
        "category_id",
        "description",
        "amount",
        "created_at",
        "updated_at",
    ];

    protected $casts = [
        // "amount" => "decimal",
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
