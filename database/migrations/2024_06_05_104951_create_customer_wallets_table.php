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
        Schema::create('customer_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->unique()->constrained()->onDelete('cascade');
            $table->string('walletId')->unique();
            $table->double('balance')->default(0);
            $table->double('in_balance')->default(0);
            $table->double('out_balance')->default(0);
            $table->timestamps();
            $table->index(['customer_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_wallets');
    }
};
