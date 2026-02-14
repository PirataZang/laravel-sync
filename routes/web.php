<?php

use Illuminate\Support\Facades\Route;

// 👇 AGORA sim ele fica por último
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
