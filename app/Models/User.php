<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'created_at',
        'updated_at',
        'active',
        'token_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // --------------------------
// --------  SCOPES  --------
// --------------------------
    public function scopeActive($query, $active = true): Builder
    {
        return $query->where('active', $active);
    }



    // -----------------------------
// --------  RELATIONS  --------
// -----------------------------

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function setting()
    {
        return $this->hasOne(UserSetting::class);
    }





}
