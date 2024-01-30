<?php

use App\Http\Controllers\DrinkController;
use App\Http\Controllers\MenuController;
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
Route::get('/api/transactions', [App\Http\Controllers\TransactionController::class, 'apiTransaction']);
Route::get('/cart', [App\Http\Controllers\TransactionController::class, 'api']);
Route::delete('/cart/{id}', [App\Http\Controllers\TransactionDetailController::class, 'destroy']);
Route::post('/addToCart', [App\Http\Controllers\TransactionController::class, 'addToCart']);
Route::post('/checkout', [App\Http\Controllers\TransactionController::class, 'checkout']);
Route::get('/bayar', [App\Http\Controllers\TransactionController::class, 'bayar'])->name('bayar');
Route::get('/success', [App\Http\Controllers\TransactionController::class, 'success'])->name('success');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
Route::get('/transactions', [App\Http\Controllers\AdminController::class, 'transaksi']);
Route::get('/menus', [App\Http\Controllers\AdminController::class, 'menu'])->name('menus');
Route::get('/drinks', [App\Http\Controllers\AdminController::class, 'minuman']);
Route::post('/addMenu', [App\Http\Controllers\MenuController::class, 'store']);
Route::post('/addMenu/{id}', [App\Http\Controllers\MenuController::class, 'update']);
Route::delete('/addMenu/{id}', [App\Http\Controllers\MenuController::class, 'destroy']);

