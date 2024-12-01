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
        Schema::create('cash_withdrawl_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('transactionId')->unique();
            $table->double('amount');
            $table->string('account_name');
            $table->string('payment_provider');
            $table->string('phone_number')->nullable();
            $table->string('payment_transaction_id')->unique()->nullable();
            $table->string('status')->default('pending'); // pending, rejected, confirmed
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->foreignId('account_id')->constrained();
            $table->dateTime('rejected_at')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedInteger('createdable_id');
            $table->char('createdable_type'); #customer ,user
            $table->timestamps();
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_withdrawl_transactions');
    }
};
