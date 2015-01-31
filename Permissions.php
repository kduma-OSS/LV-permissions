<?php namespace KDuma\Permissions;


use KDuma\Permissions\Models\Permission;
use KDuma\Permissions\Models\Role;

trait Permissions {
    protected $permissions_fetched = false;
    protected $permissions_list = [];
    protected $roles_list = [];

    public function roles()
    {
        return $this->belongsToMany('\KDuma\Permissions\Models\Role')->withTimestamps();
    }

    protected function fetchPermissions(){
        if($this->permissions_fetched == false) {
            $cache_time = config('permissions.cache_time', 60);

            $user = $this;
            $getCallback = function() use ($user)
            {
                $roles_list = [];
                $permissions_list = [];
                $user->load('roles.permissions');
                foreach ($user->roles as $role) {
                    $roles_list[$role->str_id] = $role;
                    foreach ($role->permissions as $permission) {
                        $permissions_list[$permission->str_id] = $permission;
                    }
                }
//                $user->load('member.role.permissions');
//                if(!is_null($user->member) && !is_null($user->member->role)){
//                    $role = $user->member->role;
//                    $roles_list[$role->str_id] = $role;
//                    foreach ($role->permissions as $permission) {
//                        $permissions_list[$permission->str_id] = $permission;
//                    }
//                }
                return [$roles_list, $permissions_list];
            };

            if(is_null($cache_time))
                list($this->roles_list, $this->permissions_list) = $getCallback();
            else
                list($this->roles_list, $this->permissions_list) = \Cache::remember('user.permissions.'.$user->id, $cache_time, $getCallback);

            $this->permissions_fetched = true;
        }
    }

    public function can($permission){
        $this->fetchPermissions();
        if(is_null($permission)){
            return $this->permissions_list;
        }

        $root_permission = config('permissions.root_permission');
        if($root_permission != '' && $this->is($root_permission))
            return true;
        if($permission instanceof Permission){
            return isset($this->permissions_list[$permission->str_id]);
        }elseif(is_string($permission)){
            return isset($this->permissions_list[$permission]);
        }
        throw new \Exception('Unsupported parameter');
    }

    public function is($role){
        $this->fetchPermissions();
        if($role instanceof Role){
            return isset($this->roles_list[$role->str_id]);
        }elseif(is_string($role)){
            return isset($this->roles_list[$role]);
        }elseif(is_null($role)){
            return $this->roles_list;
        }
        throw new \Exception('Unsupported parameter');
    }
}