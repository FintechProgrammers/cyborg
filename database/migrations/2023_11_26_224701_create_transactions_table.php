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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('user_id');
            $table->string('reference');
            $table->string('coin');
            $table->double('amount', 20, 8)->default(0);
            $table->enum('type', ['deposit', 'withdrawal', 'reward']);
            $table->enum('status', ['pending', 'complete', 'failed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
