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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            // Zoho Books IDs
            $table->string('zoho_invoice_id')->unique()->nullable()->comment('Zoho Books Invoice ID');
            $table->string('zoho_customer_id')->nullable()->comment('Zoho Books Customer ID');

            // Invoice Information
            $table->string('invoice_number')->unique()->comment('رقم الفاتورة');
            $table->date('invoice_date')->comment('تاريخ الفاتورة');
            $table->date('due_date')->nullable()->comment('تاريخ الاستحقاق');

            // Customer Information
            $table->string('customer_name')->comment('اسم العميل');
            $table->string('customer_email')->nullable()->comment('بريد العميل');
            $table->text('customer_address')->nullable()->comment('عنوان العميل');

            // Financial Information
            $table->decimal('subtotal', 15, 2)->default(0)->comment('المجموع الفرعي');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('قيمة الضريبة');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('قيمة الخصم');
            $table->decimal('total', 15, 2)->default(0)->comment('المجموع الإجمالي');
            $table->string('currency_code', 3)->default('SAR')->comment('رمز العملة');

            // Status
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'void', 'partially_paid'])
                  ->default('draft')
                  ->comment('حالة الفاتورة');

            // Additional Information
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->text('terms')->nullable()->comment('الشروط والأحكام');

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false)->comment('مزامنة مع Zoho');
            $table->timestamp('last_synced_at')->nullable()->comment('آخر مزامنة');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('invoice_number');
            $table->index('status');
            $table->index('invoice_date');
            $table->index('customer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
