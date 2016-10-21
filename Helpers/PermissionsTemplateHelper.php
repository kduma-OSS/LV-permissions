<?php

namespace KDuma\Permissions\Helpers;

class PermissionsTemplateHelper
{
    public function can($permissions)
    {
        return \Auth::user()->can($permissions);
    }

    public function is($role)
    {
        return \Auth::user()->is($role);
    }
}
