<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuctionController;
use App\Http\Controllers\CarAuctionApiController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// 📌 Página principal (Pública)
Route::get('/', fn() => Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
]))->name('home');

// ✅ Grupo de rutas protegidas (Solo autenticados y verificados)
Route::middleware(['auth', 'verified'])->group(function () {

    // 📌 Renderizar la lista de subastas en Inertia
    Route::get('/auctions', fn() => Inertia::render('AuctionList'))->name('auctions.list');

    // 📌 Renderizar la vista de detalle de subasta
    Route::get('/auctions/{auction}', fn($auction) => Inertia::render('AuctionDetail', [
        'auction' => $auction
    ]))->name('auctions.detail');

    // 📌 Perfil del usuario autenticado
    Route::get('/profile', fn() => Inertia::render('Profile/Edit'))->name('profile.edit');

    // 📌 Dashboard del usuario
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');

    // 📌 Administración de subastas
    Route::middleware('auth')->prefix('admin')->group(function () {
        Route::get('/', fn() => Inertia::render('Admin/Dashboard'))->name('admin.index');
        Route::get('/auctions', fn() => Inertia::render('Admin/AuctionList'))->name('admin.auctions.index');
        Route::get('/auctions/create', fn() => Inertia::render('Admin/CreateAuction'))->name('admin.auctions.create');
        Route::get('/auctions/{auction}/edit', fn($auction) => Inertia::render('Admin/EditAuction', [
            'auction' => $auction
        ]))->name('admin.auctions.edit');
    });
});

// ✅ Rutas de API

Route::prefix('api')->group(function () {
    // ✅ API para obtener todas las subastas
    Route::get('/auctions', [CarAuctionApiController::class, 'index']);

    // ✅ API para obtener detalles de una subasta
    Route::get('/auctions/{auction}', [CarAuctionApiController::class, 'show']);

    // ✅ API para realizar una puja (requiere autenticación)
    Route::middleware(['auth:sanctum'])->put('/auctions/{auction}/bid', [CarAuctionApiController::class, 'placeBid']);

    // ✅ API para administrar subastas (requiere autenticación)
    Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
        Route::post('/auctions', [CarAuctionApiController::class, 'store']);
        Route::put('/auctions/{auction}', [CarAuctionApiController::class, 'update']);
        Route::delete('/auctions/{auction}', [CarAuctionApiController::class, 'destroy']);
    });
});


// ✅ Rutas de autenticación
require __DIR__ . '/auth.php';
