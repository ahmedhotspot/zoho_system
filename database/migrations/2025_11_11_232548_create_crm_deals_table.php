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
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->id();

            // Zoho CRM ID
            $table->string('zoho_deal_id')->unique()->nullable();

            // Basic Information
            $table->string('deal_name')->nullable();
            $table->string('account_id')->nullable();
            $table->string('account_name')->nullable();
            $table->string('contact_id')->nullable();
            $table->string('contact_name')->nullable();

            // Deal Details
            $table->string('stage')->nullable(); // Qualification, Needs Analysis, Value Proposition, etc.
            $table->decimal('amount', 15, 2)->nullable();
            $table->date('closing_date')->nullable();
            $table->string('type')->nullable(); // New Business, Existing Business
            $table->string('lead_source')->nullable();
            $table->string('next_step')->nullable();
            $table->decimal('probability', 5, 2)->nullable(); // 0-100
            $table->decimal('expected_revenue', 15, 2)->nullable();

            // Campaign & Source
            $table->string('campaign_source')->nullable();

            // Owner Information
            $table->string('owner_id')->nullable();
            $table->string('owner_name')->nullable();

            // Description
            $table->text('description')->nullable();

            // Additional Fields
            $table->integer('deal_category_status')->nullable();
            $table->string('currency')->default('SAR');
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false);
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('zoho_deal_id');
            $table->index('account_id');
            $table->index('contact_id');
            $table->index('owner_id');
            $table->index('stage');
            $table->index('closing_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_deals');
    }
};
