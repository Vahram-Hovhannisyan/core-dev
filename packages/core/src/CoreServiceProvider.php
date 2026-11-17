<?php

namespace OurCore\Core;

use Illuminate\Support\ServiceProvider;
use OurCore\Core\Plugins\PluginManager;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/core.php', 'core');

        $this->app->singleton(PluginManager::class);
        $this->app->make(PluginManager::class)
            ->load(config('core.plugins', []));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/core.php' => config_path('core.php'),
        ], 'core-config');

        $this->app->make(PluginManager::class)->bootAll();
    }
}
