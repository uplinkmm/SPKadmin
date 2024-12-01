<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.e
     */ 
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date_time');
            $table->double('amount');
            $table->char('walletable_type');
            $table->unsignedBigInteger('walletable_id');
            $table->enum('action',['in','out']);
            $table->unsignedBigInteger('customer_id');
            $table->timestamps();
            $table->index(['customer_id', 'walletable_type', 'walletable_id'], 'wallet_txn_cust_walletable_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
