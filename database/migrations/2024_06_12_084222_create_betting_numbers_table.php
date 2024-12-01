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
        Schema::create('betting_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('number');
            $table->double('amount');
            $table->integer('betting_multiplier');
            $table->foreignId('betting_id')->constrained()->onDelete('cascade');
            $table->boolean('is_win')->default(0);
            $table->boolean('is_twist')->default(0);
            $table->timestamps();
            $table->index(['betting_id','is_win']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('betting_numbers');
    }
};
