<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// --------------------------
// -------  PRIVADAS  -------
// --------------------------

Route::middleware("auth.token")->group(function () {
    //? Users
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::post('/', 'index');
        Route::post('/create', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::post('/logout', 'logout');
    });

    //? Category
    Route::prefix('category')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    //? Transactions
    Route::prefix('transaction')->controller(TransactionController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
});



// --------------------------
// -------  PÚBLICAS  -------
// --------------------------
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::post('/login', 'login');
});

