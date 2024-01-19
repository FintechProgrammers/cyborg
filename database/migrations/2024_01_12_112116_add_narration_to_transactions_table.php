<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('action', ['withdrawal', 'deposit', 'reward', 'transfer'])->default('deposit')->after('type');
            $table->longText('narration')->nullable()->after('action');
        });

        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('debit', 'credit')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['narration', 'action']);
        });
    }
};
