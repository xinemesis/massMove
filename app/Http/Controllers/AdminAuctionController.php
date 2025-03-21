<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CarAuction;

class AdminAuctionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/AuctionList', [
            'auctions' => CarAuction::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/CreateAuction');
    }

    public function edit(CarAuction $auction)
    {
        return Inertia::render('Admin/EditAuction', [
            'auction' => $auction
        ]);
    }
}
