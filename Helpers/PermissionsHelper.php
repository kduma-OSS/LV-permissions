<?php
/**
 * Created by PhpStorm.
 * User: kduma
 * Date: 31/01/15
 * Time: 17:59
 */

namespace KDuma\Permissions\Helpers;


class PermissionsHelper {
    function can($permission, $user=null){
        if(is_null($user))
            $user = \Auth::user();
        $can = true;
        if(is_array($permission)){
            foreach ($permission as $perm) {
                $can = $can && $user->can($perm);
            }
        }else{
            $can = $user->can($permission);
        }
        return $can;
    }

    function denyPermissions($permission, $user=null){
        $can = self::can($permission, $user);
        if(!$can){
            abort(401);
            exit;
        }
        return true;
    }
}