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
        Schema::create('user_exchanges', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('user_id');
            $table->string('exchange_id');
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('api_passphrase')->nullable();
            $table->boolean('is_binded')->default(false);
            $table->decimal('spot_balance', 11, 8)->default(0);
            $table->decimal('future_balance', 11, 8)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_exchanges');
    }
};
