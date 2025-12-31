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
        Schema::create('game_promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable(); // e.g. "Welcome Bonus 5000"
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('deposit_amount', 12);
            $table->integer('promotion_percentage')->default(0);
            $table->decimal('promotion_amount', 12, 2); // promotion amount (calculated)
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_promotions');
    }
};
