<?php

namespace App\Http\Controllers;

use App\Models\CarAuction;
use App\Events\NewBidPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewBidNotification;
use Inertia\Inertia;

class CarAuctionController extends Controller
{
    public function index()
    {
        $auctions = CarAuction::all();
        return Inertia::render('AuctionList', ['auctions' => $auctions]);
        //return response()->json(CarAuction::all());
    }

    public function store(Request $request)
    {
        $auction = CarAuction::create($request->all());
        return response()->json($auction, 201);
    }

    public function update(Request $request, CarAuction $auction)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Validar que la nueva puja sea mayor a la actual
        $request->validate([
            'current_bid' => 'required|numeric|min:' . ($auction->current_bid + 1),
        ]);

        // Actualizar la subasta con la nueva puja y el usuario que la hizo
        $auction->update([
            'current_bid' => $request->current_bid,
            'user_id' => Auth::id()
        ]);

        // 🚀 Emitir evento en tiempo real para actualizar la interfaz
        broadcast(new NewBidPlaced($auction))->toOthers();

        // 🚀 Notificar al dueño de la subasta sobre la nueva puja
        if ($auction->user) {
            $auction->user->notify(new NewBidNotification($auction));
        }

        return response()->json(['message' => 'Puja actualizada correctamente', 'auction' => $auction]);
    }
}
