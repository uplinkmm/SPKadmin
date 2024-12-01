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
        Schema::create('bettings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('betting_no_id',120)->unique();
            $table->dateTime('date_time');
            $table->double('total_amount');
            $table->enum('time_status',['morning','evening'])->nullable();
            $table->foreignId('game_setting_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('game_id')->constrained();
            $table->softDeletes(); // Add this line for soft deletes
            $table->timestamps();
            $table->index(['game_id','customer_id','time_status','game_setting_id']);

        });
    }

    /**
     * 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bettings');
    }
};
