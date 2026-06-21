<?php

namespace App\Services\Ai\Contracts;

interface AiProviderInterface
{
    /**
     * Send a prompt to the AI and return a text response.
     */
    public function chat(string $prompt, array $context = []): string;

    /**
     * Send a prompt and return a structured (JSON) response.
     */
    public function generateStructured(string $prompt, array $schema): array;
}
