<?php namespace KDuma\Permissions;


use KDuma\Permissions\Helpers\PermissionsHelper;

trait ProtectTrait {
    protected function protect($permissions){
        $PermissionsHelper = new PermissionsHelper;
        return $PermissionsHelper->denyPermissions($permissions);
    }
}
