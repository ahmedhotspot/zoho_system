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
        Schema::create('financings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('iqama_number')->nullable();
            $table->string('application_id')->nullable();
            $table->string('financingcompanies')->nullable();
            $table->string('price')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('financing_type_id')->nullable()->constrained('financing_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financings');
    }
};
