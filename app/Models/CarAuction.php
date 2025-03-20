<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarAuction extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'starting_price', 'current_bid', 'end_time'];
}
