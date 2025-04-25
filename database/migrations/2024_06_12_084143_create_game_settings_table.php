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
        Schema::create('game_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->time('opening_time')->nullable(); #for 2D
            $table->time('closing_time')->nullable();#for 2D
            $table->time('lottery_time')->nullable();#for 2D
            $table->dateTime('opening_date_time')->nullable();#for 3D
            $table->dateTime('closing_date_time')->nullable();#for 3D
            $table->dateTime('lottery_date_time')->nullable();#for 3D
            $table->integer('bet_multiplier')->default(0);
            $table->integer('twist_multiplier')->default(10);#for 3D
            $table->integer('min')->default(0);
            $table->integer('max')->default(0);
            $table->integer('closing_amount');  #closing_amount per bet
            // $table->enum('time_status',['evening','morning']);
            $table->char('time_status')->nullable();
            $table->foreignId('game_id')->constrained();
            $table->timestamps();
            $table->boolean('is_active')->default(1);
            //lottery_game
            $table->string('photo')->nullable();
            $table->integer('limitation_qty')->default(0);
            $table->double('price');
            $table->longText('terms_and_condition')->nullable();
            $table->longText('description')->nullable();
            //end
            $table->index(['game_id','time_status']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_settings');
    }
};
