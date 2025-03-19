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
}
