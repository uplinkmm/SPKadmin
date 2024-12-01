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
        Schema::create('agent_withdrawal_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date_time');
            $table->decimal('amount',15,2);
            $table->foreignId('agent_id');
            $table->longText('remark')->nullable();
            $table->unsignedInteger('confirmed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->unsignedInteger('rejected_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->enum('status',['received','confirmed','rejected'])->default('received');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_withdrawal_transactions');
    }
};
