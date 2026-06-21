<?php

namespace Tests\Unit\Ai;

use App\Services\Ai\AiManager;
use App\Services\Ai\Contracts\AiProviderInterface;
use App\Services\Ai\Providers\DeepSeekProvider;
use App\Services\Ai\Providers\OpenAiProvider;
use Tests\TestCase;

class AiManagerTest extends TestCase
{
    public function test_default_provider_is_openai(): void
    {
        config(['app.ai_provider' => 'openai']);

        $manager = app(AiManager::class);
        $provider = $manager->provider();

        $this->assertInstanceOf(OpenAiProvider::class, $provider);
    }

    public function test_set_provider_to_deepseek(): void
    {
        $manager = app(AiManager::class);
        $manager->setProvider('deepseek');

        $provider = $manager->provider();
        $this->assertInstanceOf(DeepSeekProvider::class, $provider);
    }

    public function test_set_provider_back_to_openai(): void
    {
        $manager = app(AiManager::class);
        $manager->setProvider('deepseek');
        $manager->setProvider('openai');

        $this->assertInstanceOf(OpenAiProvider::class, $manager->provider());
    }

    public function test_invalid_provider_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $manager = app(AiManager::class);
        $manager->setProvider('invalid_provider');
    }

    public function test_chat_proxies_to_provider(): void
    {
        $mockProvider = \Mockery::mock(AiProviderInterface::class);
        $mockProvider->shouldReceive('chat')
            ->once()
            ->with('test prompt', ['key' => 'value'])
            ->andReturn('AI response');

        $manager = new AiManager;
        $reflection = new \ReflectionClass($manager);
        $property = $reflection->getProperty('provider');
        $property->setAccessible(true);
        $property->setValue($manager, $mockProvider);

        $result = $manager->chat('test prompt', ['key' => 'value']);
        $this->assertSame('AI response', $result);
    }

    public function test_generate_structured_proxies_to_provider(): void
    {
        $expected = ['brand' => 'Honda', 'model' => 'Vario'];

        $mockProvider = \Mockery::mock(AiProviderInterface::class);
        $mockProvider->shouldReceive('generateStructured')
            ->once()
            ->with('test prompt', ['brand', 'model'])
            ->andReturn($expected);

        $manager = new AiManager;
        $reflection = new \ReflectionClass($manager);
        $property = $reflection->getProperty('provider');
        $property->setAccessible(true);
        $property->setValue($manager, $mockProvider);

        $result = $manager->generateStructured('test prompt', ['brand', 'model']);
        $this->assertSame($expected, $result);
    }
}
