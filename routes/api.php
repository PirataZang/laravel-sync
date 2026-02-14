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
        Route::get('/teste', 'teste');
    });
});



// --------------------------
// -------  PÚBLICAS  -------
// --------------------------
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::post('/teste', 'testePost');
});





// 👇 AGORA sim ele fica por último
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
