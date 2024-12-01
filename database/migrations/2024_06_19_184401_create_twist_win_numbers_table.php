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
        Schema::create('twist_win_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('number');
            $table->foreignId('betting_win_id')->constrained();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes(); // Add this line for soft deletes
            $table->index('betting_win_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twist_win_numbers');
    }
};
