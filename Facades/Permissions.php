<?php

namespace KDuma\Permissions\Facades;

use Illuminate\Support\Facades\Facade;

class Permissions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'permissions.templatehelper';
    }
}
