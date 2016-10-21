<?php

namespace KDuma\Permissions\Facades;

use Illuminate\Support\Facades\Facade;

class PermissionsManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'permissions.adderhelper';
    }
}
