<?php

namespace App\Http\Controllers;

use App\Models\CarAuction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewBidPlaced;
use Inertia\Inertia;

class CarAuctionController extends Controller
{
    // 🔹 Mostrar la lista de subastas a los usuarios
    public function index()
    {
        return response()->json(CarAuction::all());
    }

    // 🔹 Ver detalles de una subasta
    public function show(CarAuction $auction)
    {
        return response()->json($auction);
    }

    // ✅ Obtener historial de pujas de una subasta
    public function getBids(CarAuction $auction)
    {
        return response()->json($auction->bids()->with('user:id,name')->orderBy('amount', 'desc')->get());
    }

    // 🔹 Función para colocar una puja
    public function placeBid(Request $request, CarAuction $auction)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $request->validate([
            'current_bid' => 'required|numeric|min:' . ($auction->current_bid + 1),
        ]);

        $bid = Bid::create([
            'auction_id' => $auction->id,
            'user_id' => Auth::id(),
            'amount' => $request->current_bid
        ]);

        $auction->update([
            'current_bid' => $request->current_bid,
            'user_id' => Auth::id()
        ]);

        broadcast(new NewBidPlaced($auction))->toOthers();

        return response()->json([
            'message' => 'Puja realizada con éxito',
            'auction' => $auction,
            'bid' => $bid
        ]);
    }
}
