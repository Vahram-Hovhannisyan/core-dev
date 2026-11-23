<?php

namespace OurCore\Core\Modules\Identity\Console;

use Illuminate\Console\Command;
use OurCore\Core\Modules\Identity\Permissions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SyncAccessCommand extends Command
{
    protected $signature = 'core:sync-access';

    protected $description = 'Create or update core roles and permissions';

    public function handle(): int
    {
        foreach (Permissions::all() as $name) {
            Permission::findOrCreate($name, 'web');
        }

        foreach (Permissions::roles() as $role => $permissions) {
            Role::findOrCreate($role, 'web')->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info('Roles and permissions synced.');

        return self::SUCCESS;
    }
}

