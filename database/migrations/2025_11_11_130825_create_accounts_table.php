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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            // Zoho Books ID
            $table->string('zoho_account_id')->unique()->nullable();

            // Account Information
            $table->string('account_name');
            $table->string('account_code')->nullable();
            $table->enum('account_type', [
                'other_asset',
                'other_current_asset',
                'cash',
                'bank',
                'fixed_asset',
                'other_current_liability',
                'credit_card',
                'long_term_liability',
                'other_liability',
                'equity',
                'income',
                'other_income',
                'expense',
                'cost_of_goods_sold',
                'other_expense'
            ])->default('expense');

            // Account Details
            $table->text('description')->nullable();
            $table->boolean('is_user_created')->default(false);
            $table->boolean('is_system_account')->default(false);
            $table->boolean('is_active')->default(true);

            // Parent Account
            $table->string('parent_account_id')->nullable();
            $table->string('parent_account_name')->nullable();

            // Depth and Hierarchy
            $table->integer('depth')->default(0);

            // Currency
            $table->string('currency_code', 3)->default('SAR');

            // Balance Information
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('bank_balance', 15, 2)->default(0);
            $table->decimal('bcy_balance', 15, 2)->default(0); // Base Currency Balance
            $table->decimal('uncategorized_transactions', 15, 2)->default(0);

            // Account Settings
            $table->boolean('is_involved_in_transaction')->default(false);
            $table->string('current_balance')->nullable();

            // Sync Information
            $table->boolean('synced_to_zoho')->default(false);
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('account_type');
            $table->index('is_active');
            $table->index('parent_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
