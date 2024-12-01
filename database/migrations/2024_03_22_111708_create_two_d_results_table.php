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
        Schema::create('two_d_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('history_id');
            $table->dateTime('stock_datetime');
            $table->date('stock_date');
            $table->string('open_time')->nullable();
            $table->string('day_part')->nullable();
            $table->string('set')->nullable();
            $table->string('value')->nullable();
            $table->string('twod')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('two_d_results');
    }
};
