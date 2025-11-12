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
        Schema::create('crm_accounts', function (Blueprint $table) {
            $table->id();

            // Zoho CRM Account ID
            $table->string('zoho_account_id')->unique()->nullable();

            // Basic Information
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_type')->nullable(); // Analyst, Competitor, Customer, Distributor, Integrator, Investor, Partner, Press, Prospect, Reseller, Supplier, Vendor, Other
            $table->string('industry')->nullable();
            $table->string('annual_revenue')->nullable();
            $table->integer('employees')->nullable();
            $table->string('ownership')->nullable();
            $table->string('rating')->nullable();
            $table->string('sic_code')->nullable();
            $table->string('ticker_symbol')->nullable();

            // Contact Information
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();

            // Address Information - Billing
            $table->string('billing_street')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_code')->nullable();
            $table->string('billing_country')->nullable();

            // Address Information - Shipping
            $table->string('shipping_street')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_code')->nullable();
            $table->string('shipping_country')->nullable();

            // Parent Account
            $table->string('parent_account_id')->nullable();
            $table->string('parent_account_name')->nullable();

            // Owner Information
            $table->string('owner_id')->nullable();
            $table->string('owner_name')->nullable();

            // Description
            $table->text('description')->nullable();

            // Timestamps from Zoho
            $table->timestamp('zoho_created_time')->nullable();
            $table->timestamp('zoho_modified_time')->nullable();

            // Sync tracking
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('zoho_account_id');
            $table->index('account_name');
            $table->index('account_type');
            $table->index('industry');
            $table->index('owner_id');
            $table->index('parent_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_accounts');
    }
};
