<?php

namespace OurCore\Core\Plugins;

use Illuminate\Contracts\Foundation\Application;

class PluginManager
{
    /** @var array<string, Plugin> */
    private array $plugins = [];

    public function __construct(private Application $app) {}

    public function load(array $classes): void
    {
        foreach ($classes as $class) {
            $plugin = $this->app->make($class);
            $plugin->register();
            $this->plugins[$plugin->name()] = $plugin;
        }
    }

    public function bootAll(): void
    {
        foreach ($this->plugins as $plugin) {
            $plugin->boot();
        }
    }

    public function all(): array
    {
        return $this->plugins;
    }
}
