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
        Schema::create('closing_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('number');
            $table->decimal('amount')->nullable();
            $table->dateTime('date_time');
            $table->char('time_status')->nullable();
            $table->foreignId('game_id')->constraint()->onDelete('cascade');
            $table->foreignId('game_setting_id')->onDelete('cascade');
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->index(['game_id','game_setting_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('closing_numbers');
    }
};
