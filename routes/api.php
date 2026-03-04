<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FinancialReportController;
use Illuminate\Support\Facades\Route;

// --------------------------
// -------  PRIVADAS  -------
// --------------------------

Route::middleware("auth.token")->group(function () {
    //? Users
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
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

    //? Financial Reports
    Route::prefix('reports')->controller(FinancialReportController::class)->group(function () {
        Route::get('/monthly-summary', 'monthlySummary');
        Route::get('/expenses-by-category', 'expensesByCategory');
        Route::get('/income-vs-expense', 'incomeVsExpense');
        Route::get('/monthly-transactions', 'monthlyTransactions');
        Route::get('/complete-dashboard', 'completeDashboard');
    });
});



// --------------------------
// -------  PÚBLICAS  -------
// --------------------------
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::post('/login', 'login');
});

