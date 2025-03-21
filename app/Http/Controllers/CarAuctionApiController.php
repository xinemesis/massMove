<?php

namespace App\Http\Controllers;

use App\Models\CarAuction;
use Illuminate\Http\Request;

class CarAuctionApiController extends Controller
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

    // ✅ Guardar una nueva subasta (Solo Administradores)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'starting_price' => 'required|numeric|min:1',
            'end_time' => 'required|date|after:now',
        ]);

        $auction = CarAuction::create($request->all());

        return response()->json([
            'message' => 'Subasta creada con éxito',
            'auction' => $auction
        ]);
    }

    // ✅ Actualizar detalles de una subasta
    public function update(Request $request, CarAuction $auction)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'starting_price' => 'required|numeric|min:1',
        ]);

        $auction->update($request->all());

        return response()->json(['message' => 'Subasta actualizada con éxito']);
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
