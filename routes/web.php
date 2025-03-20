<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// App Controllers
use App\Http\Controllers\CarAuctionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuctionController;

// RUTAS API (Sin autenticación)
Route::prefix('api')->group(function () {
    Route::apiResource('auctions', CarAuctionController::class)->except(['destroy']);
});

// RUTA PARA ELIMINAR SUBASTAS (Solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::delete('/api/auctions/{auction}', [CarAuctionController::class, 'destroy']);
});

// RUTAS DEL ADMINISTRADOR (Protegidas por `auth`)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('auctions', AdminAuctionController::class)->names('admin.auctions');
});

// PÁGINA PRINCIPAL
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// DASHBOARD (Requiere autenticación y verificación de email)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// PERFIL DEL USUARIO
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ RUTAS DE AUTENTICACIÓN
require __DIR__ . '/auth.php';
