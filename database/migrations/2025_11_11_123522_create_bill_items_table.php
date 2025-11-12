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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('bill_id')
                  ->constrained('bills')
                  ->onDelete('cascade')
                  ->comment('معرف الفاتورة');

            // Zoho Books IDs
            $table->string('zoho_item_id')->nullable()->comment('Zoho Books Item ID');
            $table->string('zoho_line_item_id')->nullable()->comment('Zoho Books Line Item ID');

            // Item Information
            $table->string('item_name')->comment('اسم المنتج/الخدمة');
            $table->text('description')->nullable()->comment('الوصف');
            $table->string('account_name')->nullable()->comment('اسم الحساب');

            // Pricing Information
            $table->decimal('quantity', 10, 2)->default(1)->comment('الكمية');
            $table->decimal('rate', 15, 2)->default(0)->comment('السعر للوحدة');
            $table->decimal('amount', 15, 2)->default(0)->comment('المبلغ الإجمالي');

            // Tax Information
            $table->string('tax_id')->nullable()->comment('معرف الضريبة');
            $table->string('tax_name')->nullable()->comment('اسم الضريبة');
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('نسبة الضريبة');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('قيمة الضريبة');

            $table->timestamps();

            // Indexes
            $table->index('bill_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
