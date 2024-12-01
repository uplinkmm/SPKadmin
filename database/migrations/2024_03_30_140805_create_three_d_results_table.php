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
        Schema::create('three_d_results', function (Blueprint $table) {
            $table->id();
            $table->dateTime('open_datetime');
            $table->date('open_date');
            $table->year('year');
            $table->string('winning_number');
            $table->string('extra_winning_numbers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('three_d_results');
    }
};
