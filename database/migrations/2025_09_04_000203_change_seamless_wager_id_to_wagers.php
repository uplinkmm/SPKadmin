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
        //
        Schema::table('wagers', function (Blueprint $table) {
            // change seamless_wager_id from bigint to string
            $table->string('seamless_wager_id')->change();

            // add new columns
            $table->string('wager_status')->after('status');
            $table->string('wager_type')->after('wager_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wagers', function (Blueprint $table) {
            //
        });
    }
};
