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
        Schema::table('crm_events', function (Blueprint $table) {
            $table->boolean('synced_to_zoho')->default(false)->after('last_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_events', function (Blueprint $table) {
            $table->dropColumn('synced_to_zoho');
        });
    }
};
