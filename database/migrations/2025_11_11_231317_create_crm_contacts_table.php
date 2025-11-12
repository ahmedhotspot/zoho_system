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
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();

            // Zoho CRM ID
            $table->string('zoho_contact_id')->unique()->nullable();

            // Basic Information
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('salutation')->nullable(); // Mr., Mrs., Ms., Dr., etc.
            $table->string('title')->nullable(); // Job title
            $table->string('department')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->string('secondary_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('other_phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('assistant')->nullable();
            $table->string('assistant_phone')->nullable();

            // Address Information - Mailing
            $table->string('mailing_street')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_zip')->nullable();
            $table->string('mailing_country')->nullable();

            // Address Information - Other
            $table->string('other_street')->nullable();
            $table->string('other_city')->nullable();
            $table->string('other_state')->nullable();
            $table->string('other_zip')->nullable();
            $table->string('other_country')->nullable();

            // Account/Company Information
            $table->string('account_id')->nullable(); // Zoho CRM Account ID
            $table->string('account_name')->nullable();
            $table->string('vendor_name')->nullable();

            // Lead Information
            $table->string('lead_source')->nullable();
            $table->date('date_of_birth')->nullable();

            // Owner Information
            $table->string('owner_id')->nullable();
            $table->string('owner_name')->nullable();

            // Social Media
            $table->string('twitter')->nullable();
            $table->string('skype_id')->nullable();

            // Additional Information
            $table->text('description')->nullable();
            $table->boolean('email_opt_out')->default(false);
            $table->string('reporting_to')->nullable(); // Contact ID they report to

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false);
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('zoho_contact_id');
            $table->index('email');
            $table->index('account_id');
            $table->index('owner_id');
            $table->index(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_contacts');
    }
};
