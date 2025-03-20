<?php

namespace App\Http\Controllers;

use App\Models\CarAuction;
use Illuminate\Http\Request;

class CarAuctionController extends Controller
{
    public function index()
    {
        return response()->json(CarAuction::all());
    }

    public function store(Request $request)
    {
        $auction = CarAuction::create($request->all());
        return response()->json($auction, 201);
    }

    public function update(Request $request, CarAuction $auction)
    {
        //return response()->json([
        //'received_data' => $request->all(),
        //'auction_id' => $auction->id ?? 'Not Found',
        //'current_bid' => $auction->current_bid ?? 'Not Found',
        //]);
        $request->validate([
            'current_bid' => 'required|numeric|min:' . ($auction->current_bid + 1),
        ]);

        $auction->update(['current_bid' => $request->current_bid]);

        return response()->json(['message' => 'Puja actualizada correctamente', 'auction' => $auction]);
    }
}
