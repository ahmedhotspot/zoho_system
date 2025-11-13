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
        Schema::create('crm_events', function (Blueprint $table) {
            $table->id();
            $table->string('zoho_event_id')->unique()->nullable();

            // Basic Information
            $table->string('event_title');
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->string('venue')->nullable();
            $table->string('location')->nullable();

            // Related To (What_Id in Zoho)
            $table->string('related_to_type')->nullable(); // Leads, Accounts, Deals, etc.
            $table->string('related_to_id')->nullable();
            $table->string('related_to_name')->nullable();

            // Participants
            $table->text('participants')->nullable(); // JSON array of participants
            $table->string('contact_name')->nullable();
            $table->string('contact_id')->nullable();

            // Owner
            $table->string('owner_id')->nullable();
            $table->string('owner_name')->nullable();

            // Additional Fields
            $table->text('description')->nullable();
            $table->boolean('send_notification')->default(false);
            $table->string('reminder')->nullable(); // e.g., "15 minutes before"
            $table->boolean('check_in_status')->default(false);
            $table->string('check_in_address')->nullable();
            $table->dateTime('check_in_time')->nullable();
            $table->string('check_in_sub_locality')->nullable();
            $table->string('check_in_city')->nullable();
            $table->string('check_in_state')->nullable();
            $table->string('check_in_country')->nullable();

            // Recurring Event
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_activity')->nullable();

            // Zoho Timestamps
            $table->dateTime('zoho_created_time')->nullable();
            $table->dateTime('zoho_modified_time')->nullable();
            $table->dateTime('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('zoho_event_id');
            $table->index('start_datetime');
            $table->index('end_datetime');
            $table->index('owner_id');
            $table->index('related_to_id');
            $table->index('contact_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_events');
    }
};
