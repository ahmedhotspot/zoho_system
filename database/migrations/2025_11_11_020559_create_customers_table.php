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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Zoho Books IDs
            $table->string('zoho_contact_id')->unique()->nullable();
            $table->string('zoho_currency_id')->nullable();

            // Basic Information
            $table->string('contact_name');
            $table->string('company_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('vendor_name')->nullable();
            $table->enum('contact_type', ['customer', 'vendor', 'both'])->default('customer');
            $table->enum('customer_sub_type', ['business', 'individual'])->default('business');
            $table->enum('status', ['active', 'inactive', 'crm'])->default('active');

            // Contact Details
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('website')->nullable();

            // Social Media
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();

            // Financial Information
            $table->string('currency_code', 3)->default('SAR');
            $table->integer('payment_terms')->default(0); // Days
            $table->string('payment_terms_label')->nullable();
            $table->decimal('outstanding_receivable_amount', 15, 2)->default(0);
            $table->decimal('outstanding_payable_amount', 15, 2)->default(0);
            $table->decimal('unused_credits_receivable_amount', 15, 2)->default(0);
            $table->decimal('unused_credits_payable_amount', 15, 2)->default(0);

            // Portal Access
            $table->enum('portal_status', ['enabled', 'disabled'])->default('disabled');

            // Integration
            $table->boolean('is_linked_with_zohocrm')->default(false);
            $table->string('source')->default('user'); // user, api, import, etc.
            $table->string('language_code')->nullable();

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false);
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('contact_name');
            $table->index('email');
            $table->index('contact_type');
            $table->index('status');
            $table->index('synced_to_zoho');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
