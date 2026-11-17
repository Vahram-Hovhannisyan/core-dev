<?php

namespace OurCore\Core\Plugins;

abstract class Plugin
{
    abstract public function name(): string;

    public function register(): void {}   // привязки в контейнере

    public function boot(): void {}       // маршруты, события, админка
}
