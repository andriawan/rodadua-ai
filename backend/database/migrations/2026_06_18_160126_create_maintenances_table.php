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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motorcycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Maintenance Details
            $table->string('type'); // e.g., oil change, tire replacement
            $table->text('description');
            $table->integer('odometer_km');
            $table->date('maintenance_date');
            
            // Cost & Status
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('workshop')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'pending'])->default('pending');
            
            // Next Maintenance
            $table->integer('next_maintenance_km')->nullable();
            $table->date('next_maintenance_date')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['motorcycle_id', 'maintenance_date']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
