<?php

use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/api/menus', [App\Http\Controllers\MenuController::class, 'api']);
Route::get('/api/drinks', [App\Http\Controllers\DrinkController::class, 'api']);
Route::get('/api/transaction', [App\Http\Controllers\TransactionController::class, 'transactionApi']);
Route::get('/cart', [App\Http\Controllers\TransactionController::class, 'api']);
Route::delete('/cart/{id}', [App\Http\Controllers\TransactionDetailController::class, 'destroy']);
Route::post('/addToCart', [App\Http\Controllers\TransactionController::class, 'addToCart']);
Route::post('/checkout', [App\Http\Controllers\TransactionController::class, 'checkout']);
Route::get('/bayar', [App\Http\Controllers\TransactionController::class, 'bayar'])->name('bayar');
Route::get('/success', [App\Http\Controllers\TransactionController::class, 'success'])->name('success');

