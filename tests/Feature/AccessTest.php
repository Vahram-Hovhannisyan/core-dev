<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OurCore\Core\Modules\Identity\Permissions;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('core:sync-access')->assertSuccessful();
});

it('gives a manager access to the catalog', function () {
    $user = User::factory()->create();
    $user->assignRole('manager');

    expect($user->can(Permissions::ADMIN_ACCESS))->toBeTrue();
    expect($user->can(Permissions::CATALOG_MANAGE))->toBeTrue();
});

it('keeps a customer out of the admin', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');

    expect($user->can(Permissions::ADMIN_ACCESS))->toBeFalse();
    expect($user->can(Permissions::CATALOG_MANAGE))->toBeFalse();
});

it('can be synced repeatedly without duplicates', function () {
    $this->artisan('core:sync-access')->assertSuccessful();

    expect(Permission::count())
        ->toBe(count(Permissions::all()));
});
