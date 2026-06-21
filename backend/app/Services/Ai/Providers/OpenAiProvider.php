<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\AiProviderInterface;
use OpenAI\Client;
use OpenAI\Factory;

class OpenAiProvider implements AiProviderInterface
{
    private Client $client;

    private string $model;

    private int $maxTokens;

    private float $temperature;

    public function __construct()
    {
        $config = config('services.openai');

        $this->client = (new Factory())
            ->withApiKey($config['api_key'] ?? '')
            ->withOrganization($config['organization'] ?? '')
            ->make();

        $this->model = $config['model'] ?? 'gpt-4o-mini';
        $this->maxTokens = $config['max_tokens'] ?? 2000;
        $this->temperature = $config['temperature'] ?? 0.7;
    }

    public function chat(string $prompt, array $context = []): string
    {
        $messages = [];

        if (! empty($context)) {
            $messages[] = [
                'role' => 'system',
                'content' => $this->buildSystemMessage($context),
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $prompt,
        ];

        $response = $this->client->chat()->create([
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
        ]);

        return $response->choices[0]->message->content ?? '';
    }

    public function generateStructured(string $prompt, array $schema): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are a structured data generator. Respond only with valid JSON matching the requested schema.',
            ],
            [
                'role' => 'user',
                'content' => $prompt,
            ],
        ];

        $response = $this->client->chat()->create([
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
            'response_format' => ['type' => 'json_object'],
        ]);

        $content = $response->choices[0]->message->content ?? '{}';

        return json_decode($content, true) ?? [];
    }

    private function buildSystemMessage(array $context): string
    {
        $parts = [
            'You are a knowledgeable Indonesian motorcycle assistant.',
            'Respond in Bahasa Indonesia unless otherwise specified.',
            'Be specific to Indonesian motorcycle models, roads, and conditions.',
        ];

        if (isset($context['motorcycle'])) {
            $parts[] = sprintf(
                'Motorcycle: %s %s (%d) with %d km odometer.',
                $context['motorcycle']['brand'] ?? '',
                $context['motorcycle']['model'] ?? '',
                $context['motorcycle']['year'] ?? 0,
                $context['motorcycle']['odometer_km'] ?? 0
            );
        }

        return implode("\n", $parts);
    }
}
