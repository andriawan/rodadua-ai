<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Dependencies: 0001_01_01_000000_create_users_table.php (FK: user_id).
     * Reversed by: 2026_06_18_160106 (drop).
     */
    public function up(): void
    {
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Basic Information
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('color')->nullable();
            $table->string('license_plate')->unique()->nullable();
            
            // Specifications
            $table->integer('engine_cc')->nullable();
            $table->string('engine_type')->nullable(); // e.g., 4-stroke, 2-stroke
            $table->string('transmission')->nullable(); // e.g., automatic, manual
            $table->string('fuel_type')->nullable(); // e.g., petrol, diesel
            
            // Ownership
            $table->date('purchase_date')->nullable();
            $table->integer('odometer_km')->default(0);
            $table->text('notes')->nullable();
            
            // Status
            $table->enum('status', ['active', 'inactive', 'for_sale'])->default('active');
            $table->boolean('is_favorite')->default(false);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for search and filtering
            $table->index(['brand', 'model']);
            $table->index(['user_id', 'status']);
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};
