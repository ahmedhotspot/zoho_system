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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Zoho Books IDs
            $table->string('zoho_payment_id')->unique()->nullable()->comment('Zoho Books Payment ID');
            $table->string('zoho_customer_id')->nullable()->comment('Zoho Books Customer ID');
            $table->string('zoho_invoice_id')->nullable()->comment('Zoho Books Invoice ID (if single invoice)');

            // Payment Information
            $table->string('payment_number')->unique()->nullable()->comment('Payment Number');
            $table->date('payment_date')->comment('Payment Date');
            $table->decimal('amount', 15, 2)->default(0)->comment('Payment Amount');
            $table->string('payment_mode')->nullable()->comment('Payment Mode: cash, check, creditcard, etc.');
            $table->string('reference_number')->nullable()->comment('Reference Number');

            // Customer Information
            $table->string('customer_name')->nullable()->comment('Customer Name');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');

            // Invoice Information (if payment is for specific invoice)
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');

            // Financial Information
            $table->decimal('amount_applied', 15, 2)->default(0)->comment('Amount Applied to Invoices');
            $table->string('currency_code', 3)->default('SAR')->comment('Currency Code');

            // Additional Information
            $table->text('description')->nullable()->comment('Payment Description/Notes');
            $table->string('bank_charges')->nullable()->comment('Bank Charges');
            $table->string('tax_account_id')->nullable()->comment('Tax Account ID');

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false)->comment('Synced to Zoho');
            $table->timestamp('last_synced_at')->nullable()->comment('Last Synced At');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('payment_number');
            $table->index('payment_date');
            $table->index('customer_name');
            $table->index('zoho_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
