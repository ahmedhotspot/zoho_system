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
        Schema::create('financing_price_histories', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('financing_id')->constrained('financings')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('User who made the update');
            
            // Price Information
            $table->decimal('old_price', 15, 2)->comment('السعر القديم');
            $table->decimal('new_price', 15, 2)->comment('السعر الجديد');
            
            // Additional Information
            $table->text('notes')->nullable()->comment('ملاحظات');
            
            $table->timestamps();
            
            // Indexes
            $table->index('financing_id');
            $table->index('company_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financing_price_histories');
    }
};

