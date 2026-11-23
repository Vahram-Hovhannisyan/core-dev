<?php

namespace OurCore\Core\Modules\Identity\Concerns;

use Spatie\Permission\Traits\HasRoles;

trait HasCoreAccess
{
    use HasRoles;
}
