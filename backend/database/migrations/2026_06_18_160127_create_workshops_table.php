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
     * Reversed by: 2026_06_18_160127 (drop).
     */
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Location
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Ratings & Reviews
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('specialist_motorcycle_count')->default(0);
            
            // Operating Hours
            $table->string('operating_hours')->nullable();
            $table->boolean('is_open_weekends')->default(false);
            
            // Services
            $table->text('services_offered')->nullable(); // JSON or text
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('city');
            $table->index('rating');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
