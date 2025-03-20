<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarAuctionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuctionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// RUTA PÚBLICA PARA MOSTRAR SUBASTAS (SIN AUTENTICACIÓN)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// AUTENTICACIÓN: VER PERFIL, ACTUALIZAR Y ELIMINAR CUENTA
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// DASHBOARD (SOLO USUARIOS AUTENTICADOS)
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

// GRUPO DE RUTAS PARA ADMINISTRACIÓN (PROTEGIDAS CON `auth`)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('auctions', AdminAuctionController::class)->names('admin.auctions');
});

// RUTA PARA VER EL USUARIO AUTENTICADO (USADO EN VUE.JS)
Route::middleware('auth:sanctum')->get('/user', function () {
    return response()->json(Auth::user());
});

// GRUPO DE RUTAS API PARA SUBASTAS (SIN AUTENTICACIÓN)
Route::prefix('api')->group(function () {
    Route::get('/auctions', [CarAuctionController::class, 'index']);
    Route::get('/auctions/{auction}', [CarAuctionController::class, 'show']);
});

// RUTAS PROTEGIDAS PARA OPERACIONES EN SUBASTAS (PUJAS Y ELIMINACIÓN)
Route::middleware('auth:sanctum')->prefix('api')->group(function () {
    Route::post('/auctions', [CarAuctionController::class, 'store']);
    Route::put('/auctions/{auction}', [CarAuctionController::class, 'update']);
    Route::delete('/auctions/{auction}', [CarAuctionController::class, 'destroy']);
});

// Ruta para mostrar la lista de subastas en la web
Route::get('/auctions', [CarAuctionController::class, 'index'])->name('auctions.list');

// EVENTO PARA PROBAR PUSHER
/*use App\Events\TestPusherEvent;

Route::get('/test-pusher', function () {
    broadcast(new TestPusherEvent('¡Pusher está funcionando!'));
    return response()->json(['message' => 'Evento enviado a Pusher']);
});*/

// RUTA DE AUTENTICACIÓN
require __DIR__ . '/auth.php';
