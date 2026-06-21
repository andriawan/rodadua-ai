<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Dependencies: none (standalone table, no FKs).
     * Reversed by: 2026_06_18_160129 (drop).
     */
    public function up(): void
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('part_number')->unique();
            $table->string('oem_number')->nullable();
            
            // Classification
            $table->string('category'); // e.g., engine, transmission, brake
            $table->string('subcategory')->nullable();
            $table->string('brand')->nullable();
            
            // Compatibility
            $table->json('compatible_motorcycles')->nullable();
            $table->json('compatible_years')->nullable();
            
            // Pricing & Availability
            $table->decimal('retail_price', 10, 2)->nullable();
            $table->decimal('wholesale_price', 10, 2)->nullable();
            $table->integer('quantity_available')->default(0);
            $table->boolean('in_stock')->default(false);
            
            // Supplier Information
            $table->string('supplier_name')->nullable();
            $table->string('supplier_code')->nullable();
            
            // Meta
            $table->text('notes')->nullable();
            $table->integer('view_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('category');
            $table->index('part_number');
            $table->index('in_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
