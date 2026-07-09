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
        Schema::table('raffle_entries', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        foreach (DB::table('raffle_entries')->get() as $entry) {
            DB::table('raffle_entries')
                ->where('id', $entry->id)
                ->update(['name' => trim($entry->first_name.' '.$entry->last_name)]);
        }

        Schema::table('raffle_entries', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'mobile_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raffle_entries', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('mobile_number')->nullable()->after('last_name');
        });

        foreach (DB::table('raffle_entries')->get() as $entry) {
            DB::table('raffle_entries')
                ->where('id', $entry->id)
                ->update(['first_name' => $entry->name]);
        }

        Schema::table('raffle_entries', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
