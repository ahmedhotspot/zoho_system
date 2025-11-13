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
        Schema::create('crm_notes', function (Blueprint $table) {
            $table->id();

            // Zoho CRM Fields
            $table->string('zoho_note_id')->unique()->nullable();
            $table->string('note_title')->nullable();
            $table->text('note_content')->nullable();

            // Parent Module Information (What this note is attached to)
            $table->string('parent_module')->nullable(); // Leads, Contacts, Deals, Accounts, etc.
            $table->string('parent_id')->nullable(); // Zoho ID of the parent record
            $table->string('parent_name')->nullable(); // Name of the parent record

            // Owner Information
            $table->string('owner_id')->nullable();
            $table->string('owner_name')->nullable();

            // Created/Modified By
            $table->string('created_by_id')->nullable();
            $table->string('created_by_name')->nullable();
            $table->string('modified_by_id')->nullable();
            $table->string('modified_by_name')->nullable();

            // Timestamps from Zoho
            $table->timestamp('zoho_created_time')->nullable();
            $table->timestamp('zoho_modified_time')->nullable();

            // Sync tracking
            $table->timestamp('last_synced_at')->nullable();

            // Laravel timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('zoho_note_id');
            $table->index('parent_module');
            $table->index('parent_id');
            $table->index('owner_id');
            $table->index(['parent_module', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_notes');
    }
};
