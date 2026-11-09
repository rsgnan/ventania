<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Redireciona a raiz do ERP diretamente para o painel principal
Route::redirect('/', '/dashboard')->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::resource('users', UserController::class)
    ->only(['index', 'store', 'update'])
    ->middleware(['auth']);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
