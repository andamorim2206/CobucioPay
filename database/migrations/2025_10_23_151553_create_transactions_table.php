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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['deposit', 'transfer']);
            $table->uuid('sender_wallet_id')->nullable();
            $table->uuid('receiver_wallet_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'completed', 'reverted', 'failed'])->default('pending');
            $table->timestamps();

            $table->foreign('sender_wallet_id')
                ->references('id')
                ->on('wallets')
                ->onDelete('cascade')
            ;

            $table->foreign('receiver_wallet_id')
                ->references('id')
                ->on('wallets')
                ->onDelete('cascade')
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['receiver_wallet_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['sender_wallet_id']);
        });

        Schema::dropIfExists('transactions');
    }
};
