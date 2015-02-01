<?php
/**
 * Created by PhpStorm.
 * User: kduma
 * Date: 26/01/15
 * Time: 22:11
 */

namespace KDuma\Permissions\Helpers;



use KDuma\Permissions\Models\Permission;
use KDuma\Permissions\Models\Role;

class PermissionsAdderHelper {

    function createRole($str, $name){
        $role = Role::firstOrNew(['str_id' => $str]);
        $role->name = $name;
        $role->save();
        return $role;
    }

    function deleteRole($str){
        Role::where('str_id', $str)->delete();
        return true;
    }

    function createPermission($str, $name){
        $permission = Permission::firstOrNew(['str_id' => $str]);
        $permission->name = $name;
        $permission->save();
        return $permission;
    }
    function deletePermission($str){
        Permission::where('str_id', $str)->delete();
        return true;
    }

    function attach($roles, $permissions){
        list($roles_id_list, $permissions_id_list) = $this->getIdList($roles, $permissions);
        if(empty($roles_id_list))
            return false;
        if(empty($permissions_id_list))
            return false;

        foreach($roles_id_list as $role){
            Role::find($role)->permissions()->sync($permissions_id_list, false);
        }



    }

    function detach($roles, $permissions){
        list($roles_id_list, $permissions_id_list) = $this->getIdList($roles, $permissions);
        if(empty($roles_id_list))
            return false;
        if(empty($permissions_id_list))
            return false;

        foreach($roles_id_list as $role){
            Role::find($role)->permissions()->detach($permissions_id_list);
        }
    }

    /**
     * @param $roles
     * @param $permissions
     * @return array
     */
    protected function getIdList($roles, $permissions)
    {
        $roles_id_list = [];
        if (is_array($roles)) {
            foreach ($roles as $role) {
                $roles_id_list[] = $this->getIdListHelper($role, true);
            }
        } else {
            $roles_id_list[] = $this->getIdListHelper($roles, true);
        }
        $permissions_id_list = [];
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                $permissions_id_list[] = $this->getIdListHelper($permission, false);
            }
        } else {
            $permissions_id_list[] = $this->getIdListHelper($permissions, false);
        }
        return array($roles_id_list, $permissions_id_list);
    }

    /**
     * @param $thing
     */
    protected function getIdListHelper($thing, $role=false)
    {
        if (is_object($thing)) {
            return $thing->id;
        } elseif (is_int($thing)) {
            return $thing;
        } else {
            if($role)
                return Role::where('str_id', $thing)->firstOrFail()->id;
            return Permission::where('str_id', $thing)->firstOrFail()->id;
        }
    }

}
