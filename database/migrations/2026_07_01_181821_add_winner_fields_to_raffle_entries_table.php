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
        Schema::table('raffle_entries', function (Blueprint $table) {
            $table->boolean('is_winner')->default(false)->after('mobile_number');
            $table->timestamp('won_at')->nullable()->after('is_winner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raffle_entries', function (Blueprint $table) {
            $table->dropColumn(['is_winner', 'won_at']);
        });
    }
};
