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
        Schema::create('lottery_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('number');
            $table->double('amount');
            $table->foreignId('lottery_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('lottery_winning_number_id')->nullable();
            $table->timestamps();
            $table->index(['lottery_id','lottery_winning_number_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lottery_numbers');
    }
};
