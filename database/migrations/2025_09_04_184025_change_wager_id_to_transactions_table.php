<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE transactions DROP COLUMN wager_id");

            DB::statement("
        ALTER TABLE transactions
        ADD COLUMN wager_id VARCHAR(191)
        GENERATED ALWAYS AS (NULLIF(json_unquote(json_extract(meta, '$.wager_id')), '')) STORED
    ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
