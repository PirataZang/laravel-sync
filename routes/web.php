<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');


Route::middleware('auth.token')->get('/user', function () {
    return response()->json(['ok']);
});
