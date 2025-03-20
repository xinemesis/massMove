<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CarAuction;
use Carbon\Carbon;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close';
    protected $description = 'Cierra automáticamente las subastas vencidas';

    public function handle()
    {
        $closed = CarAuction::where('end_time', '<', Carbon::now())
            ->where('status', 'open')
            ->update(['status' => 'closed']);

        $this->info("$closed subastas vencidas cerradas.");
    }
}
