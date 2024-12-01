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
        Schema::create('agent_wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date_time');
            $table->decimal('amount',15,2);
            $table->unsignedInteger('agent_id');
            $table->unsignedInteger('walletable_id');
            $table->char('walletable_type');
            $table->enum('action',['in','out']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_wallets');
    }
};
