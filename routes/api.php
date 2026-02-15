<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// --------------------------
// -------  PRIVADAS  -------
// --------------------------

Route::middleware("auth.token")->group(function () {
    // Users
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/logout', 'logout');
    });
});



// --------------------------
// -------  PÚBLICAS  -------
// --------------------------
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::post('/login', 'login');
});

