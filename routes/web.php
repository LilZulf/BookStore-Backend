<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::prefix('dashboard')->middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('books', BookController::class);
    Route::resource('transactions', TransactionController::class);
    Route::get('transactions/{id}/status/{status}', [TransactionController::class, 'changeStatus'])->name('transactions.changeStatus');
});


Route::get('midtrans/success', [MidtransController::class, 'success']);
Route::get('midtrans/unfinish', [MidtransController::class, 'unfinish']);
Route::get('midtrans/error', [MidtransController::class, 'error']);

Route::get('unauthorized', ([DashboardController::class, 'unauthorized']))->name('unauthorized');
