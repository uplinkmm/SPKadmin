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
        $indexName = null;
        $indexes = DB::select("SHOW INDEXES FROM wagers");
        foreach ($indexes as $index) {
            if ($index->Column_name === 'seamless_wager_id' && $index->Non_unique == 0) {
                $indexName = $index->Key_name; // usually wagers_seamless_wager_id_unique
                break;
            }
        }

        if ($indexName) {
            Schema::table('wagers', function (Blueprint $table) use ($indexName) {
                $table->dropUnique($indexName);
            });
        }

        Schema::table('wagers', function (Blueprint $table) {
            // Change seamless_wager_id to string and reapply unique
            $table->string('seamless_wager_id')->unique()->change();

            // Add new nullable columns
            $table->string('wager_status')->nullable()->after('status');
            $table->string('wager_type')->nullable()->after('wager_status');
            $table->unsignedBigInteger('product_id')->nullable()->after('wager_type');
            $table->unsignedBigInteger('game_type_id')->nullable()->after('product_id');
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
