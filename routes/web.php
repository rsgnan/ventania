<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redireciona a raiz do ERP diretamente para o painel principal
Route::redirect('/', '/dashboard')->name('home');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::resource('users', UserController::class)
    ->only(['index', 'store', 'update'])
    ->middleware(['auth']);

Route::resource('products', ProductController::class)
    ->only(['index', 'store', 'update'])
    ->middleware(['auth']);

Route::resource('stock-movements', StockMovementController::class)
    ->only(['index', 'store'])
    ->middleware(['auth']);

Route::resource('sales', SaleController::class)
    ->only(['index', 'store', 'update'])
    ->middleware(['auth']);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
