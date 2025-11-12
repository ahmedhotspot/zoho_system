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
        Schema::create('crm_calls', function (Blueprint $table) {
            $table->id();

            // Zoho CRM Call ID
            $table->string('zoho_call_id')->unique()->nullable();

            // Basic Information
            $table->string('subject')->nullable();
            $table->string('call_type')->nullable(); // Outbound, Inbound, Missed
            $table->string('call_purpose')->nullable();
            $table->dateTime('call_start_time')->nullable();
            $table->string('call_duration')->nullable(); // in minutes
            $table->string('call_result')->nullable();

            // Related To
            $table->string('related_to_type')->nullable(); // Leads, Contacts, Deals, Accounts
            $table->string('related_to_id')->nullable();
            $table->string('related_to_name')->nullable();

            // Contact/Lead Information
            $table->string('who_id_type')->nullable(); // Contacts, Leads
            $table->string('who_id')->nullable();
            $table->string('who_name')->nullable();

            // Owner Information
            $table->string('owner_id')->nullable();
            $table->string('owner_name')->nullable();

            // Additional Information
            $table->text('description')->nullable();
            $table->text('call_agenda')->nullable();
            $table->string('voice_recording')->nullable();
            $table->string('outgoing_call_status')->nullable();
            $table->string('caller_id')->nullable();
            $table->string('dialled_number')->nullable();

            // Timestamps from Zoho
            $table->timestamp('zoho_created_time')->nullable();
            $table->timestamp('zoho_modified_time')->nullable();

            // Sync tracking
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('zoho_call_id');
            $table->index('call_type');
            $table->index('call_start_time');
            $table->index('owner_id');
            $table->index('related_to_id');
            $table->index('who_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_calls');
    }
};
