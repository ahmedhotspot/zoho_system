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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            // Zoho Books IDs
            $table->string('zoho_bill_id')->unique()->nullable()->comment('Zoho Books Bill ID');
            $table->string('zoho_vendor_id')->nullable()->comment('Zoho Books Vendor ID');

            // Bill Information
            $table->string('bill_number')->unique()->comment('رقم الفاتورة');
            $table->date('bill_date')->comment('تاريخ الفاتورة');
            $table->date('due_date')->nullable()->comment('تاريخ الاستحقاق');
            $table->string('reference_number')->nullable()->comment('رقم المرجع');

            // Vendor Information
            $table->string('vendor_name')->comment('اسم المورد');
            $table->string('vendor_email')->nullable()->comment('بريد المورد');

            // Financial Information
            $table->decimal('subtotal', 15, 2)->default(0)->comment('المجموع الفرعي');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('قيمة الضريبة');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('قيمة الخصم');
            $table->decimal('total', 15, 2)->default(0)->comment('المجموع الإجمالي');
            $table->decimal('balance', 15, 2)->default(0)->comment('الرصيد المتبقي');
            $table->string('currency_code', 3)->default('SAR')->comment('رمز العملة');

            // Status
            $table->enum('status', ['draft', 'open', 'paid', 'overdue', 'void', 'partially_paid'])
                  ->default('draft')
                  ->comment('حالة الفاتورة');

            // Additional Information
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->text('terms')->nullable()->comment('الشروط والأحكام');

            // Payment Information
            $table->decimal('payment_made', 15, 2)->default(0)->comment('المبلغ المدفوع');
            $table->boolean('is_item_level_tax_calc')->default(false)->comment('حساب الضريبة على مستوى الصنف');

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false)->comment('مزامنة مع Zoho');
            $table->timestamp('last_synced_at')->nullable()->comment('آخر مزامنة');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('bill_number');
            $table->index('status');
            $table->index('bill_date');
            $table->index('vendor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
