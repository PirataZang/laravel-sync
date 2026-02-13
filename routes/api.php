<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth.token')->get('/user', function () {
    return response()->json(['ok']);
});

Route::get('/user/teste', function () {
    return ['ok' => true];
});

// 👇 AGORA sim ele fica por último
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
