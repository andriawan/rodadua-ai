<?php

namespace App\Providers;

use App\Services\Ai\AiManager;
use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AiManager::class, function () {
            return new AiManager();
        });
    }

    public function boot(): void
    {
        //
    }
}
