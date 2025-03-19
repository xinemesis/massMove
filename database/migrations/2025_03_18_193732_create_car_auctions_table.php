<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('car_auctions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('starting_price', 10, 2);
            $table->decimal('current_bid', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->timestamp('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_auctions');
    }
};
