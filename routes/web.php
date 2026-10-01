<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('home');

Route::get('/login', [LoginController::class, 'show'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.store');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/products', [ProductController::class, 'index'])
    ->middleware('auth')
    ->name('products.index');

Route::get('/products/create', [ProductController::class, 'create'])
    ->middleware('auth')
    ->name('products.create');

Route::post('/products', [ProductController::class, 'store'])
    ->middleware('auth')
    ->name('products.store');

Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
    ->middleware('auth')
    ->name('products.edit');

Route::put('/products/{product}', [ProductController::class, 'update'])
    ->middleware('auth')
    ->name('products.update');
