# L5-permissions


# Setup
Add the package to the require section of your composer.json and run `composer update`

    "kduma/permissions": "1.0.*@dev"

Then add the Service Provider to the providers array in `config/app.php`:

    'KDuma\Permissions\PermissionsServiceProvider',

Then add the Facades to the aliases array in `config/app.php`:

    'Permissions'        => 'KDuma\Permissions\Facades',
    'PermissionsManager' => 'KDuma\Permissions\Facades\PermissionsManager'
    
in your `User` model (`app/User.php`) add following line:

    use \KDuma\Permissions\Permissions;
    
Add following line in your base controller (`app/Http/Controllers/Controller.php`):

    use \KDuma\Permissions\ProtectTrait;


# Usage
## Protecting controllers
In controller you can use `protect` method. 

```PHP
$this->protect('permission');
```
or
   
```PHP
$this->protect(['list', 'of', 'required', 'permission']);
```
    
## Checking permissions in views
You can use `Permissions` facade:
```PHP
Permissions::can('permission');
```
or
```PHP
Permissions::is('role');
```
    
    
    
    
    
    
    
    
    
    
    
    
# Extending

## Models

You are free to extend `Permission` and `Role` models.  
If you extend, set model path in configuration (in `ConfigServiceProvider`): 

```PHP
config([ 'permissions.models.Role' => '\App\Role' ]);
```

Sample extended model:

```PHP
namespace App;

use KDuma\Permissions\Models\Role as BaseRole;

class Role extends BaseRole
{
    public function members()
    {
        return $this->hasMany('\App\Member');
    }
}
```


## Roles and Permissions from associated models

For example if your user is associated to an Member object that have own role, 
you can import them by placing this sample method in your `User` model:

```PHP
protected function fetchAddionalPermissions($roles_list, $permissions_list)
{
    $this->load('member.role.permissions');
    if(!is_null($this->member) && !is_null($this->member->role)){
        $role = $this->member->role;
        $roles_list[$role->str_id] = $role;
        foreach ($role->permissions as $permission) {
            $permissions_list[$permission->str_id] = $permission;
        }
    }
    return [$roles_list, $permissions_list];
}
```