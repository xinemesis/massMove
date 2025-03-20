<?php

namespace App\Http\Controllers;

use App\Models\CarAuction;
use Illuminate\Http\Request;

class AdminAuctionController extends Controller
{
    public function index()
    {
        $auctions = CarAuction::all();
        return view('admin.auctions.index', compact('auctions'));
    }

    public function create()
    {
        return view('admin.auctions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'starting_price' => 'required|numeric|min:0',
            'end_time' => 'required|date'
        ]);

        CarAuction::create([
            'name' => $request->name,
            'starting_price' => $request->starting_price,
            'current_bid' => 0, // La primera puja siempre es 0
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('admin.auctions.index')->with('success', 'Subasta creada correctamente.');
    }

    public function edit(CarAuction $auction)
    {
        return view('admin.auctions.edit', compact('auction'));
    }

    public function update(Request $request, CarAuction $auction)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'starting_price' => 'required|numeric|min:0',
            'end_time' => 'required|date'
        ]);

        $auction->update($request->all());

        return redirect()->route('admin.auctions.index')->with('success', 'Subasta actualizada.');
    }

    public function destroy(CarAuction $auction)
    {
        $auction->delete();
        return redirect()->route('admin.auctions.index')->with('success', 'Subasta eliminada.');
    }
}
