<?php
/**
 * Created by PhpStorm.
 * User: kduma
 * Date: 31/01/15
 * Time: 17:59.
 */
namespace KDuma\Permissions\Helpers;

class PermissionsHelper
{
    public function can($permission, $user = null)
    {
        if (is_null($user)) {
            $user = \Auth::user();
        }

        $can = true;
        if (is_array($permission)) {
            foreach ($permission as $perm) {
                $can = $can && $user->can($perm);
            }
        } else {
            $can = $user->can($permission);
        }

        return $can;
    }

    public function is($role, $user = null)
    {
        if (is_null($user)) {
            $user = \Auth::user();
        }
        $is = true;
        if (is_array($role)) {
            foreach ($role as $rol) {
                $is = $is && $user->is($rol);
            }
        } else {
            $is = $user->is($role);
        }

        return $is;
    }

    public function denyPermissions($permission, $user = null)
    {
        $can = self::can($permission, $user);
        if (! $can) {
            abort(401);
        }

        return true;
    }
}
