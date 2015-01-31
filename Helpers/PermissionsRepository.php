<?php
/**
 * Created by PhpStorm.
 * User: kduma
 * Date: 26/01/15
 * Time: 22:11
 */

namespace App\Repositories;


use App\Permission;
use App\Role;

class PermissionsRepository {
    public $permissions = [];
    public $roles = [];
    public $role_permissions = [];


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



    function reloadFromDatabase(){
        $this->permissions = [];
        $this->role_permissions = [];
        $this->role_permissions = [];

        $roles = Role::with('permissions')->get();
        foreach ($roles as $role) {
            $this->roles[$role->str_id] = $role->name;
//            $ids=[];
            foreach ($role->permissions as $permission) {
//                $this->permissions[$permission->str_id] = $permission->name;
                $this->role_permissions[$role->str_id][$permission->str_id]=$permission->str_id;
//                $ids[$permission->id]=$permission->id;
            }
//            $role->permissions()->sync($ids);
        }
        foreach (Permission::all() as $permission) {
            $this->permissions[$permission->str_id] = $permission->name;
//            $this->role_permissions[$role->str_id][$permission->str_id]=$permission->str_id;
        }
    }

    function removeRole($str){
		\App\Role::where('str_id', $str)->delete();
        unset($this->roles[$str]);
	}

    function createRole($str, $name){
		$role = \App\Role::firstOrNew(['str_id' => $str]);
		$role->name = $name;
		$role->save();
        $this->roles[$str] = $name;
	}

    function removePermission($str){
        \App\Permission::where('str_id', $str)->delete();
    }

    function createPermission($str, $name){
		$permission = \App\Permission::firstOrNew(['str_id' => $str]);
		$permission->name = $name;
		$permission->save();
        $this->permissions[$str] = $name;
        unset($this->permissions[$str]);
	}

    function attachPermission($role_txt, $permission, $add=null){
        if(!is_null($add)){
            if(is_array($add)) {
                foreach ($add as $a) {
                    $this->attachPermission($role_txt, $this->role_permissions[$a]);
                }
            }else{
                $this->attachPermission($role_txt, $this->role_permissions[$add]);
            }
        }
        $role = \App\Role::where('str_id', $role_txt)->firstOrFail();
        if(is_array($permission)){
            foreach($permission as $p){
                $this->role_permissions[$role_txt][$p]=$p;
                $p = \App\Permission::where('str_id', $p)->firstOrFail();
                $role->permissions()->sync([$p->id], false);
            }
        }else{
            $this->role_permissions[$role_txt][$permission]=$permission;
            $permission = \App\Permission::where('str_id', $permission)->firstOrFail();
            $role->permissions()->sync([$permission->id], false);
        }
    }

    function dettachPermission($role_txt, $permission){
        // TODO
        throw new exception('Not Inplemented');
    }

}