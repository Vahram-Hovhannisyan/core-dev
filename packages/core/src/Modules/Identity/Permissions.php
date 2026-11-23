<?php

namespace OurCore\Core\Modules\Identity;

final class Permissions
{
    public const ADMIN_ACCESS = 'admin.access';

    public const CATALOG_VIEW = 'catalog.view';

    public const CATALOG_MANAGE = 'catalog.manage';

    /** @return list<string> */
    public static function all(): array
    {
        return [
            self::ADMIN_ACCESS,
            self::CATALOG_VIEW,
            self::CATALOG_MANAGE,
        ];
    }

    /** @return array<string, list<string>> */
    public static function roles(): array
    {
        return [
            'admin' => self::all(),
            'manager' => [
                self::ADMIN_ACCESS,
                self::CATALOG_VIEW,
                self::CATALOG_MANAGE,
            ],
            'customer' => [],
        ];
    }
}
