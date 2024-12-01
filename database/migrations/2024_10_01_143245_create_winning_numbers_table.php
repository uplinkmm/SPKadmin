<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('winning_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('two_d')->nulable();
            $table->string('modern')->nullable();
            $table->string('internet')->nullable();
            $table->string('tw')->nullable();
            $table->string('set');
            $table->string('value');
            $table->dateTime('date_time');
            $table->string('lottery_time');
            $table->date('date');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('winning_numbers');
    }
};
