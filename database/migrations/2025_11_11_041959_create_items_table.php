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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // Zoho Books IDs
            $table->string('zoho_item_id')->unique()->comment('Zoho Books Item ID');

            // Basic Information
            $table->string('name')->comment('اسم المنتج/الخدمة');
            $table->string('sku')->nullable()->comment('رمز المنتج (SKU)');
            $table->text('description')->nullable()->comment('الوصف');
            $table->enum('item_type', ['sales', 'purchases', 'sales_and_purchases', 'inventory'])->default('sales')->comment('نوع المنتج');
            $table->enum('product_type', ['goods', 'service'])->default('goods')->comment('نوع المنتج (سلعة/خدمة)');

            // Pricing Information
            $table->decimal('rate', 15, 2)->default(0)->comment('السعر');
            $table->decimal('purchase_rate', 15, 2)->nullable()->comment('سعر الشراء');

            // Tax Information
            $table->string('tax_id')->nullable()->comment('معرف الضريبة');
            $table->string('tax_name')->nullable()->comment('اسم الضريبة');
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('نسبة الضريبة');
            $table->enum('tax_type', ['tax', 'tax_group'])->nullable()->comment('نوع الضريبة');

            // Account Information
            $table->string('account_id')->nullable()->comment('معرف الحساب');
            $table->string('account_name')->nullable()->comment('اسم الحساب');
            $table->string('purchase_account_id')->nullable()->comment('معرف حساب الشراء');
            $table->string('purchase_account_name')->nullable()->comment('اسم حساب الشراء');
            $table->string('inventory_account_id')->nullable()->comment('معرف حساب المخزون');
            $table->string('inventory_account_name')->nullable()->comment('اسم حساب المخزون');

            // Inventory Information (for inventory items)
            $table->decimal('initial_stock', 15, 2)->nullable()->comment('المخزون الأولي');
            $table->decimal('stock_on_hand', 15, 2)->nullable()->comment('المخزون الحالي');
            $table->decimal('reorder_level', 15, 2)->nullable()->comment('مستوى إعادة الطلب');
            $table->string('unit')->nullable()->comment('الوحدة (كجم، قطعة، إلخ)');

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('الحالة');
            $table->boolean('is_taxable')->default(true)->comment('خاضع للضريبة');
            $table->boolean('is_returnable')->default(false)->comment('قابل للإرجاع');

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false)->comment('تمت المزامنة مع Zoho');
            $table->timestamp('last_synced_at')->nullable()->comment('آخر مزامنة');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('zoho_item_id');
            $table->index('name');
            $table->index('sku');
            $table->index('status');
            $table->index('item_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
