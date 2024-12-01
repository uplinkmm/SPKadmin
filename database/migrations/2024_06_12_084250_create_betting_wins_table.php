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
        Schema::create('betting_wins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('number');
            $table->dateTime('date_time');
            $table->enum('time_status',['morning','evening']);
            $table->boolean('is_approved')->default(0);
            $table->foreignId('game_setting_id')->constrained();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->softDeletes(); // Add this line for soft deletes
            $table->timestamps();
            $table->index(['time_status','game_setting_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('betting_wins');
    }
};
