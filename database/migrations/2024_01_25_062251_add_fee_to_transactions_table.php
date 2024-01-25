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
        Schema::table('transactions', function (Blueprint $table) {
            $table->double('fee', 20, 8)->default(0)->after('amount');
            $table->string('address')->nullable()->after('fee');
            $table->json('response_payload')->nullable()->after('narration');
            $table->json('request_payload')->nullable()->after('response_payload');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['fee', 'address', 'response_payload', 'request_payload']);
        });
    }
};
