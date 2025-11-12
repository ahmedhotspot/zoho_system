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
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();

            // Zoho CRM ID
            $table->string('zoho_lead_id')->unique()->nullable();

            // Basic Information
            $table->string('first_name')->nullable();
            $table->string('last_name');
            $table->string('full_name')->nullable();
            $table->string('company')->nullable();
            $table->string('title')->nullable(); // Job Title
            $table->string('designation')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();

            // Address Information
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();

            // Lead Information
            $table->enum('lead_status', ['Not Contacted', 'Contacted', 'Qualified', 'Unqualified', 'Junk Lead', 'Lost Lead', 'Pre-Qualified', 'Attempted to Contact'])->default('Not Contacted');
            $table->string('lead_source')->nullable(); // Web, Phone, Email, etc.
            $table->string('industry')->nullable();
            $table->integer('no_of_employees')->nullable();
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->string('rating')->nullable(); // Hot, Warm, Cold

            // Additional Information
            $table->string('skype_id')->nullable();
            $table->string('twitter')->nullable();
            $table->string('secondary_email')->nullable();
            $table->text('description')->nullable();

            // Owner Information
            $table->string('owner_id')->nullable();
            $table->string('owner_name')->nullable();

            // Conversion Information
            $table->boolean('is_converted')->default(false);
            $table->timestamp('converted_at')->nullable();
            $table->string('converted_contact_id')->nullable();
            $table->string('converted_account_id')->nullable();
            $table->string('converted_deal_id')->nullable();

            // System Fields
            $table->string('created_by_id')->nullable();
            $table->string('created_by_name')->nullable();
            $table->string('modified_by_id')->nullable();
            $table->string('modified_by_name')->nullable();

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false);
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('email');
            $table->index('phone');
            $table->index('company');
            $table->index('lead_status');
            $table->index('lead_source');
            $table->index('is_converted');
            $table->index('synced_to_zoho');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_leads');
    }
};
