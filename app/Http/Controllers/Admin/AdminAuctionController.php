<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarAuction;
use App\Events\NewAuctionCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminAuctionController extends Controller
{
    // 🔹 Mostrar lista de subastas en el panel de admin
    public function index()
    {
        return response()->json(CarAuction::all());
    }

    // 🔹 Guardar nueva subasta en la base de datos
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
        //return redirect()->route('admin.auctions.index')->with('success', 'Subasta creada con éxito.');
    }

    // 🔹 Actualizar una subasta existente
    public function update(Request $request, CarAuction $auction)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'starting_price' => 'required|numeric|min:1',
        ]);

        $auction->update([
            'name' => $request->name,
            'starting_price' => $request->starting_price,
        ]);

        return response()->json(['message' => 'Subasta actualizada con éxito', 'auction' => $auction]);
    }

    // 🔹 Eliminar una subasta
    public function destroy(CarAuction $auction)
    {
        $auction->delete();
        //return redirect()->route('admin.auctions.index')->with('success', 'Subasta eliminada.');
    }
}
