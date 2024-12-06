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
        Schema::create('customer_game_wallets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('description')
                ->nullable();
            $table->json('meta')
                ->nullable();

            // $table->double('promotion_balance')->default(0);
            // $table->double('game_balance')->default(0);
            // $table->double('in_balance')->default(0);
            // $table->double('out_balance')->default(0);

            //update for laravel wallet system 
            $table->decimal('promotion_balance', 64, 2)->default(0);
            $table->decimal('game_balance', 64, 2)->default(0);
            $table->decimal('in_balance', 64, 2)->default(0);
            $table->decimal('balaout_balancence', 64, 2)->default(0);
            $table->decimal('balance', 64, 2)->default(0);
            $table->unsignedSmallInteger('decimal_places')
                ->default(2);
            $table->enum('type',['game','main']);
            //end laravel wallet 

            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_game_wallets');
    }
};
