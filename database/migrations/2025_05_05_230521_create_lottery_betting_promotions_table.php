<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lottery_betting_promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('lottery_id')->constrained()->onDelete('cascade');
            $table->foreignId('lottery_promotion_ticket_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->index(['lottery_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lottery_betting_promotions');
    }
};
