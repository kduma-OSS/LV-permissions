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

//    function reloadFromDatabase(){
//        $this->permissions = [];
//        $this->role_permissions = [];
//
//        $roles = Role::with('permissions')->get();
//        foreach ($roles as $role) {
//            $this->roles[$role->str_id] = $role->name;
//            foreach ($role->permissions as $permission) {
//                $this->role_permissions[$role->str_id][$permission->str_id]=$permission->str_id;
//            }
//        }
//        foreach (Permission::all() as $permission) {
//            $this->permissions[$permission->str_id] = $permission->name;
//        }
//    }


    /**
     * @param $thing
     */
    protected function getIDList($thing, $role=false)
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


    function attach($roles, $permissions){
        $roles_id_list = [];
        if(is_array($roles)){
            foreach ($roles as $role) {
                $roles_id_list[] = $this->getIDList($role, true);
            }
        }else{
            $roles_id_list[] = $this->getIDList($roles, true);
        }
        if(empty($roles_id_list))
            return false;

        $permissions_id_list = [];
        if(is_array($permissions)){
            foreach ($permissions as $permission) {
                $permissions_id_list[] = $this->getIDList($permission, true);
            }
        }else{
            $permissions_id_list[] = $this->getIDList($permissions, true);
        }
        if(empty($permissions_id_list))
            return false;

        foreach($roles_id_list as $role){
            Role::find($role)->permissions()->sync($permissions_id_list, false);
        }



    }





//    function attachPermission($role_txt, $permission, $add=null){
//        if(!is_null($add)){
//            if(is_array($add)) {
//                foreach ($add as $a) {
//                    $this->attachPermission($role_txt, $this->role_permissions[$a]);
//                }
//            }else{
//                $this->attachPermission($role_txt, $this->role_permissions[$add]);
//            }
//        }
//        $role = Role::where('str_id', $role_txt)->firstOrFail();
//        if(is_array($permission)){
//            foreach($permission as $p){
//                $this->role_permissions[$role_txt][$p]=$p;
//                $p = Permission::where('str_id', $p)->firstOrFail();
//                $role->permissions()->sync([$p->id], false);
//            }
//        }else{
//            $this->role_permissions[$role_txt][$permission]=$permission;
//            $permission = Permission::where('str_id', $permission)->firstOrFail();
//            $role->permissions()->sync([$permission->id], false);
//        }
//    }

    function dettach($roles, $permissions){
        // TODO To be inplemented
        throw new exception('Not Inplemented');
    }



}