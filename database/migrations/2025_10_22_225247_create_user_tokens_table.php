<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // força engine
            $table->id();
            $table->uuid('user_id');
            $table->string('token')->unique();
            $table->string('device')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
            ;
        });
    }

    public function down(): void
    {
        Schema::table('user_tokens', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('user_tokens');
    }
};
