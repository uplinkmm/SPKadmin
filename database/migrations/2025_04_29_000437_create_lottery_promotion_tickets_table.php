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
        Schema::create('lottery_promotion_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('qty');
            $table->integer('additional_qty');
            $table->foreignId('lottery_promotion_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->index(['lottery_promotion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lottery_promotion_tickets');
    }
};
