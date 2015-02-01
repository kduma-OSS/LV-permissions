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

//    public $permissions = [];
//    public $roles = [];
//    public $role_permissions = [];


    function createRole($str, $name){
        $role = Role::firstOrNew(['str_id' => $str]);
        $role->name = $name;
        $role->save();
        return $role;
//        $this->roles[$str] = $name;
    }

    function deleteRole($str){
        Role::where('str_id', $str)->delete();
        return true;
//        unset($this->roles[$str]);
    }

    function createPermission($str, $name){
        $permission = Permission::firstOrNew(['str_id' => $str]);
        $permission->name = $name;
        $permission->save();
        return $permission;
//        $this->permissions[$str] = $name;
//        unset($this->permissions[$str]);
    }
    function deletePermission($str){
        Permission::where('str_id', $str)->delete();
        return true;
    }

    function attach($roles, $permissions){
        list($roles_id_list, $permissions_id_list) = $this->_getIDList($roles, $permissions);
        if(empty($roles_id_list))
            return false;
        if(empty($permissions_id_list))
            return false;

        foreach($roles_id_list as $role){
            Role::find($role)->permissions()->sync($permissions_id_list, false);
        }



    }

    function detach($roles, $permissions){
        list($roles_id_list, $permissions_id_list) = $this->_getIDList($roles, $permissions);
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
    protected function _getIDList($roles, $permissions)
    {
        $roles_id_list = [];
        if (is_array($roles)) {
            foreach ($roles as $role) {
                $roles_id_list[] = $this->_getIDListHelper($role, true);
            }
        } else {
            $roles_id_list[] = $this->_getIDListHelper($roles, true);
        }
        $permissions_id_list = [];
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                $permissions_id_list[] = $this->_getIDListHelper($permission, false);
            }
        } else {
            $permissions_id_list[] = $this->_getIDListHelper($permissions, false);
        }
        return array($roles_id_list, $permissions_id_list);
    }

    /**
     * @param $thing
     */
    protected function _getIDListHelper($thing, $role=false)
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
