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
        Schema::create('troubleshooting_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motorcycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Problem Details
            $table->text('problem_description');
            $table->string('symptom');
            $table->text('ai_analysis')->nullable(); // AI generated analysis
            $table->json('suggested_solutions')->nullable(); // Array of solutions
            
            // Diagnosis
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'resolved', 'in_progress'])->default('open');
            
            // Resolution
            $table->text('resolution_notes')->nullable();
            $table->date('resolved_date')->nullable();
            $table->text('workshop_feedback')->nullable();
            
            // AI Provider Used
            $table->string('ai_provider')->nullable(); // 'openai' or 'deepseek'
            $table->text('prompt_used')->nullable();
            
            // Rating
            $table->integer('user_rating')->nullable(); // 1-5
            $table->text('user_feedback')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['motorcycle_id', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index('severity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('troubleshooting_histories');
    }
};
