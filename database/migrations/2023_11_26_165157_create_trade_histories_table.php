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
        Schema::create('trade_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('user_id');
            $table->string('exchange_id');
            $table->string('market');
            $table->double('trade_price', 20, 8)->default(0);
            $table->double('profit', 20, 8)->default(0);
            $table->double('quantity', 20, 8)->default(0);
            $table->enum('trade_type', ['spot', 'future']);
            $table->enum('type', ['buy', 'sell']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_histories');
    }
};
