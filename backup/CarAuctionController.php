<?php

namespace App\Http\Controllers;

use App\Models\CarAuction;
use App\Models\Bid;
use App\Events\NewAuctionCreated;
use App\Events\NewBidPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarAuctionController extends Controller
{
    // ✅ Obtener todas las subastas en formato JSON
    public function index()
    {
        return response()->json(CarAuction::all());
    }

    // ✅ Obtener detalles de una subasta específica
    public function show(CarAuction $auction)
    {
        return response()->json($auction);
    }

    // ✅ Obtener historial de pujas de una subasta
    public function getBids(CarAuction $auction)
    {
        return response()->json($auction->bids()->with('user:id,name')->orderBy('amount', 'desc')->get());
    }

    // ✅ Guardar una nueva subasta (Solo Administradores)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'starting_price' => 'required|numeric|min:0',
            'end_time' => 'required|date|after:now',
        ]);

        $auction = CarAuction::create($validated);

        // 🔹 Emitir evento en tiempo real
        broadcast(new NewAuctionCreated($auction))->toOthers();

        return response()->json(['message' => 'Subasta creada con éxito', 'auction' => $auction]);
    }

    // ✅ Actualizar detalles de una subasta
    public function update(Request $request, CarAuction $auction)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // ✅ Asegurarnos de que solo validamos la puja y no el nombre ni el precio inicial
        $request->validate([
            'current_bid' => 'required|numeric|min:' . ($auction->current_bid), // La puja debe ser mayor
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

        // 🚀 Emitir evento en tiempo real
        broadcast(new NewBidPlaced($auction))->toOthers();

        return response()->json([
            'message' => 'Puja realizada con éxito',
            'auction' => $auction,
            'bid' => $bid
        ]);
    }


    // ✅ Eliminar una subasta
    public function destroy(CarAuction $auction)
    {
        $auction->delete();

        return response()->json(['message' => 'Subasta eliminada']);
    }

    // ✅ Realizar una puja en la subasta
    public function placeBid(Request $request, CarAuction $auction)
    {
        $request->validate([
            'current_bid' => 'required|numeric|min:' . ($auction->current_bid),
        ]);

        $auction->update(['current_bid' => $request->current_bid]);

        return response()->json([
            'message' => 'Puja realizada con éxito',
            'auction' => $auction
        ]);
    }
}
