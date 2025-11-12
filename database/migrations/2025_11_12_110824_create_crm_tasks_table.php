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
        Schema::create('crm_tasks', function (Blueprint $table) {
            $table->id();

            // Zoho CRM Task ID
            $table->string('zoho_task_id')->unique()->nullable();

            // Basic Information
            $table->string('subject')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->nullable(); // Not Started, Deferred, In Progress, Completed, Waiting for input
            $table->string('priority')->nullable(); // High, Highest, Low, Lowest, Normal

            // Related To
            $table->string('related_to_type')->nullable(); // Leads, Contacts, Deals, Accounts
            $table->string('related_to_id')->nullable();
            $table->string('related_to_name')->nullable();

            // Contact Information
            $table->string('contact_id')->nullable();
            $table->string('contact_name')->nullable();

            // Owner Information
            $table->string('owner_id')->nullable();
            $table->string('owner_name')->nullable();

            // Additional Information
            $table->text('description')->nullable();
            $table->boolean('send_notification_email')->default(false);
            $table->string('reminder')->nullable();
            $table->boolean('repeat')->default(false);

            // Timestamps from Zoho
            $table->timestamp('zoho_created_time')->nullable();
            $table->timestamp('zoho_modified_time')->nullable();
            $table->timestamp('closed_time')->nullable();

            // Sync tracking
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('zoho_task_id');
            $table->index('status');
            $table->index('priority');
            $table->index('due_date');
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
        Schema::dropIfExists('crm_tasks');
    }
};
