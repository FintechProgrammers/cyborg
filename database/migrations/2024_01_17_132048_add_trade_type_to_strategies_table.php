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
        Schema::table('strategies', function (Blueprint $table) {
            $table->enum('trade_type', ['spot', 'future'])->after('market_id');
            $table->string('capital')->default(0)->after('trade_type');
            $table->enum('strategy_mode', ['short', 'long'])->default('short')->after('capital');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('strategies', function (Blueprint $table) {
            $table->dropColumn(['trade_type', 'capital', 'strategy_mode']);
        });
    }
};
