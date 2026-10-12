<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpkCalculationController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\DashboardController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.home');

Route::get('/spk', [SpkCalculationController::class, 'index'])
    ->name('dashboard.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route CRUD Produk
    Route::resource('produk', ProdukController::class);

    // Route Sistem Kasir
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
});