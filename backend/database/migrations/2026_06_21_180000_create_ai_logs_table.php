<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Dependencies: users table (FK: user_id).
     * Reversed by: 2026_06_21_180000 (drop).
     */
    public function up(): void
    {
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider'); // 'openai' or 'deepseek'
            $table->string('model'); // e.g., 'gpt-4o-mini', 'deepseek-chat'
            $table->string('prompt_key')->nullable()->comment('Key from ai_prompts table');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->unsignedSmallInteger('tokens_prompt')->nullable();
            $table->unsignedSmallInteger('tokens_completion')->nullable();
            $table->unsignedSmallInteger('tokens_total')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->string('status'); // 'success', 'error'
            $table->text('error_message')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('provider');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
