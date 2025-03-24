<?php

use App\Http\Controllers\Admin\AdminAuctionController;
use App\Models\CarAuction;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarAuctionController;
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
    Route::get('/auctions/{auction}', fn(CarAuction $auction) => Inertia::render('AuctionDetail', [
        'auctionId' => $auction->id
    ]))->name('auctions.detail');

    // 📌 Perfil del usuario autenticado
    Route::get('/profile', fn() => Inertia::render('Profile/Edit'))->name('profile.edit');

    // 📌 Dashboard del usuario
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');

    // 📌 Administración de subastas (Solo admins)
    Route::middleware('auth')->prefix('admin')->group(function () {
        Route::get('/', fn() => Inertia::render('Admin/Dashboard'))->name('admin.index');
        Route::get('/auctions', fn() => Inertia::render('Admin/AuctionList'))->name('admin.auctions.index');
        Route::get('/auctions/create', fn() => Inertia::render('Admin/CreateAuction'))->name('admin.auctions.create');
        Route::get('/auctions/{auction}/edit', fn(CarAuction $auction) => Inertia::render('Admin/EditAuction', [
            'auctionId' => $auction->id
        ]))->name('admin.auctions.edit');
    });

    // ✅ API de subastas (JSON)
    Route::prefix('api')->group(function () {
        // 📌 Rutas públicas de la API
        Route::get('/auctions', [CarAuctionController::class, 'index']);
        Route::get('/auctions/{auction}', [CarAuctionController::class, 'show']);
        Route::get('/auctions/{auction}/bids', [CarAuctionController::class, 'getBids']);

        // 📌 Rutas protegidas con autenticación (Solo usuarios autenticados pueden realizar estas acciones)
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::put('/auctions/{auction}/bid', [CarAuctionController::class, 'placeBid']);
            Route::post('/auctions', [AdminAuctionController::class, 'store']);
            Route::put('/auctions/{auction}', [AdminAuctionController::class, 'update']);
            Route::delete('/auctions/{auction}', [AdminAuctionController::class, 'destroy']);
        });
    });
});

// ✅ Rutas de autenticación
require __DIR__ . '/auth.php';
