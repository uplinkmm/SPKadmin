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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number')->unique();
            $table->string('password')->nullable();
            $table->string('otp');
            $table->boolean('is_verified')->default(0);
            $table->dateTime('verified_at')->nullable();
            $table->string('email')->nullable();
            $table->string('email_verified_at')->nullable();
            $table->string('provider_name')->nullable();
            $table->string('provider_id')->nullable();
            $table->integer('two_d_limit')->default(100000);
            $table->integer('three_d_limit')->default(100000);
            $table->unsignedInteger('agent_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
