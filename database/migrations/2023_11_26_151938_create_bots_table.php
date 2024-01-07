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
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('user_id');
            $table->string('exchange_id');
            $table->string('market_id');
            $table->boolean('started')->default(false);
            $table->boolean('running')->default(false);
            $table->enum('trade_type', ['spot', 'future']);
            $table->enum('strategy_mode', ['long', 'short'])->default('short');
            $table->boolean('is_copied')->default(false);
            $table->boolean('active_copy')->default(false);
            $table->json('settings')->nullable();
            $table->json('trade_values')->nullable();
            $table->longText('logs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bots');
    }
};
