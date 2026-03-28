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
        Schema::create('threed_default_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('bet_multiplier')->default(0);
            $table->integer('twist_multiplier')->default(0);
            $table->integer('closing_amount')->default(0);
            $table->integer('min')->default(0);
            $table->integer('max')->default(0); 
            $table->unsignedInteger('updated_by')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threed_default_settings');
    }
};
