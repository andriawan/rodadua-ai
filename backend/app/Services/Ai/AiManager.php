<?php

namespace App\Services\Ai;

use App\Services\Ai\Contracts\AiProviderInterface;
use App\Services\Ai\Providers\DeepSeekProvider;
use App\Services\Ai\Providers\OpenAiProvider;
use InvalidArgumentException;

class AiManager
{
    private ?AiProviderInterface $provider = null;

    public function provider(): AiProviderInterface
    {
        if ($this->provider !== null) {
            return $this->provider;
        }

        $driver = config('app.ai_provider', config('services.ai_provider', 'openai'));

        $this->provider = match ($driver) {
            'openai' => app(OpenAiProvider::class),
            'deepseek' => app(DeepSeekProvider::class),
            default => throw new InvalidArgumentException("Unsupported AI provider: {$driver}"),
        };

        return $this->provider;
    }

    public function setProvider(string $driver): void
    {
        $this->provider = match ($driver) {
            'openai' => app(OpenAiProvider::class),
            'deepseek' => app(DeepSeekProvider::class),
            default => throw new InvalidArgumentException("Unsupported AI provider: {$driver}"),
        };
    }

    /**
     * Proxy call to the active provider's chat method.
     */
    public function chat(string $prompt, array $context = []): string
    {
        return $this->provider()->chat($prompt, $context);
    }

    /**
     * Proxy call to the active provider's generateStructured method.
     */
    public function generateStructured(string $prompt, array $schema): array
    {
        return $this->provider()->generateStructured($prompt, $schema);
    }
}
