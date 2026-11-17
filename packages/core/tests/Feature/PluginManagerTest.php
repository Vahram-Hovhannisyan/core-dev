<?php

use OurCore\Core\Plugins\Plugin;
use OurCore\Core\Plugins\PluginManager;

class FakePlugin extends Plugin
{
    public static bool $booted = false;

    public function name(): string
    {
        return 'fake';
    }

    public function boot(): void
    {
        self::$booted = true;
    }
}

it('loads and boots plugins', function () {
    FakePlugin::$booted = false;

    $manager = new PluginManager(app());
    $manager->load([FakePlugin::class]);
    $manager->bootAll();

    expect($manager->all())->toHaveKey('fake');
    expect(FakePlugin::$booted)->toBeTrue();
});
