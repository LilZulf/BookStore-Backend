<?php

use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\UserController;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function(){
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateUser']);
    Route::post('user/photo', [UserController::class, 'updatePhoto']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('transactions', [Transactions::class, 'all']);
    Route::put('transactions/{id}', [TransactionsController::class, 'update']);
    Route::post('checkout', [TransactionsController::class, 'checkout']);
});
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::get('books', [BooksController::class, 'all']);

Route::post('midtrans/callback', [MidtransController::class, 'callback']);