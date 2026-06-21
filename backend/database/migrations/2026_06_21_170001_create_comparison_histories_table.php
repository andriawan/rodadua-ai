<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Dependencies: users table, motorcycles table.
     * Reversed by: 2026_06_21_170001 (drop).
     */
    public function up(): void
    {
        Schema::create('comparison_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('motorcycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('compared_motorcycle_id')->constrained('motorcycles')->cascadeOnDelete();
            $table->json('comparison_data')->comment('Structured comparison results');
            $table->text('summary')->nullable()->comment('AI-generated comparison summary');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('motorcycle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparison_histories');
    }
};
