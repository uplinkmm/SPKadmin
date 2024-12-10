<?php

declare(strict_types=1);

use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create($this->table(), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('payable');
            $table->unsignedBigInteger('wallet_id');
            $table->enum('type', ['deposit', 'withdraw'])->index();
            $table->decimal('amount', 64, 0);
            $table->boolean('confirmed');
            $table->json('meta')
                ->nullable()
            ;
            $table->uuid('uuid')
                ->unique();
            $table->boolean('is_report_generated')->default(false)->index();
            //$table->unsignedBigInteger('agent_id')->nullable();
            $table->string('event_id', 191)
            ->nullable()
                ->generatedAs("json_unquote(json_extract(meta, '$.event_id')) STORED");
            $table->string('seamless_transaction_id', 191)->nullable()
                ->generatedAs("json_unquote(json_extract(meta, '$.seamless_transaction_id')) STORED");
            $table->bigInteger('wager_id')->nullable()
                ->generatedAs("json_unquote(json_extract(meta, '$.wager_id')) STORED");
            $table->text('note')->nullable()
                ->generatedAs("json_unquote(json_extract(meta, '$.note')) STORED");
            $table->bigInteger('target_user_id')->nullable()
                ->generatedAs("json_unquote(json_extract(meta, '$.target_user_id')) STORED");

            $table->timestamps();

            $table->index(['payable_type', 'payable_id'], 'payable_type_payable_id_ind');
            $table->index(['payable_type', 'payable_id', 'type'], 'payable_type_ind');
            $table->index(['payable_type', 'payable_id', 'confirmed'], 'payable_confirmed_ind');
            $table->index(['payable_type', 'payable_id', 'type', 'confirmed'], 'payable_type_confirmed_ind');
        });
    }

    public function down(): void
    {
        Schema::drop($this->table());
    }

    private function table(): string
    {
        return (new Transaction())->getTable();
    }
};
