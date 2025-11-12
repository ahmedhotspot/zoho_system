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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();

            // Zoho Books IDs
            $table->string('zoho_estimate_id')->unique()->nullable()->comment('Zoho Books Estimate ID');
            $table->string('zoho_customer_id')->nullable()->comment('Zoho Books Customer ID');

            // Estimate Information
            $table->string('estimate_number')->unique()->comment('رقم عرض السعر');
            $table->date('estimate_date')->comment('تاريخ عرض السعر');
            $table->date('expiry_date')->nullable()->comment('تاريخ الانتهاء');
            $table->string('reference_number')->nullable()->comment('الرقم المرجعي');

            // Customer Information
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null')->comment('معرف العميل');
            $table->string('customer_name')->comment('اسم العميل');
            $table->string('customer_email')->nullable()->comment('بريد العميل');
            $table->text('customer_address')->nullable()->comment('عنوان العميل');

            // Financial Information
            $table->decimal('subtotal', 15, 2)->default(0)->comment('المجموع الفرعي');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('قيمة الضريبة');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('قيمة الخصم');
            $table->decimal('adjustment', 15, 2)->default(0)->comment('التعديل');
            $table->decimal('total', 15, 2)->default(0)->comment('المجموع الإجمالي');
            $table->string('currency_code', 3)->default('SAR')->comment('رمز العملة');

            // Status
            $table->enum('status', ['draft', 'sent', 'accepted', 'declined', 'invoiced', 'expired'])
                  ->default('draft')
                  ->comment('حالة عرض السعر');

            // Additional Information
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->text('terms')->nullable()->comment('الشروط والأحكام');
            $table->string('salesperson_name')->nullable()->comment('اسم مندوب المبيعات');

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false)->comment('مزامنة مع Zoho');
            $table->timestamp('last_synced_at')->nullable()->comment('آخر مزامنة');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('estimate_number');
            $table->index('status');
            $table->index('estimate_date');
            $table->index('customer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
