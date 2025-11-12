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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // Zoho Books IDs
            $table->string('zoho_expense_id')->unique()->nullable();
            $table->string('zoho_account_id')->nullable();
            $table->string('zoho_customer_id')->nullable();
            $table->string('zoho_vendor_id')->nullable();
            $table->string('zoho_project_id')->nullable();

            // Foreign Keys
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');

            // Expense Information
            $table->string('account_name')->nullable();
            $table->date('expense_date');
            $table->decimal('amount', 15, 2);
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();

            // Tax Information
            $table->string('tax_id')->nullable();
            $table->string('tax_name')->nullable();
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->boolean('is_inclusive_tax')->default(false);

            // Currency Information
            $table->string('currency_id')->nullable();
            $table->string('currency_code', 10)->default('SAR');
            $table->decimal('exchange_rate', 15, 6)->default(1);

            // Amounts
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2);

            // Billing Information
            $table->boolean('is_billable')->default(false);
            $table->boolean('is_personal')->default(false);
            $table->string('customer_name')->nullable();

            // Status
            $table->enum('status', ['unbilled', 'invoiced', 'reimbursed', 'nonbillable'])->default('unbilled');

            // Invoice Information (if billed)
            $table->string('invoice_id')->nullable();
            $table->string('invoice_number')->nullable();

            // Project Information
            $table->string('project_name')->nullable();

            // Vendor Information
            $table->string('vendor_name')->nullable();

            // Receipt Information
            $table->string('expense_receipt_name')->nullable();
            $table->string('expense_receipt_type')->nullable();

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false);
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('expense_date');
            $table->index('status');
            $table->index('customer_id');
            $table->index('is_billable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
