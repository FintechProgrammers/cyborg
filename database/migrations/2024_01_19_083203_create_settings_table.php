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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->enum('withdrawal_status', \App\Models\Settings::STATUS)->default(\App\Models\Settings::STATUS['Disable']);
            $table->enum('automatic_withdrawal', \App\Models\Settings::STATUS)->default(\App\Models\Settings::STATUS['Disable']);
            $table->string('minimum_widthdrawal')->nullable(0);
            $table->string('maximum_widthdrawal')->nullable(0);
            $table->string('withdrawal_fee')->nullable(0);
            $table->enum('trade_status', \App\Models\Settings::STATUS)->default(\App\Models\Settings::STATUS['Disable']);
            $table->string('trade_fee')->nullable(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
