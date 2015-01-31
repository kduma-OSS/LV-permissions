# L5-permissions
Simple (really?) package to provide Roles and Permissions to Laravel 5

# Setup
Add the package to the require section of your composer.json and run `composer update`

    "kduma/permissions": "~1.0"

Then add the Service Provider to the providers array in `config/app.php`:

    'KDuma\Permissions\PermissionsServiceProvider',

Then add the Facades to the aliases array in `config/app.php`:

    'Permissions'        => 'KDuma\Permissions\Facades',
    'PermissionsManager' => 'KDuma\Permissions\Facades\PermissionsManager'
    
in your `User` model (`app/User.php`) add following line:

    use \KDuma\Permissions\Permissions;
    
Add following line in your base controller (`app/Http/Controllers/Controller.php`):

    use \KDuma\Permissions\ProtectTrait;

Import migrations from migrations folder. First 4 are necessary, last two are examples you can use or make new based on.

- In `0000_00_00_000005_create_and_assign_roles_and_permissions.php` migration file are sample Roles and Permissions
- In `0000_00_00_000006_create_administrator_account.php` migration file is created administrator account

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
    
## Creating/deleting roles and permissions
To manage roles and permissions you can use `PermissionsManager` Facaade.  
The easiest way to manage permissions is putting them in migrations files.  

`PermissionsManager` Facaade has bunch of usefull methods:

- Roles
    - `PermissionsManager::createRole('ROLE_STRING_ID', 'ROLE_DESCRIPTION');`
    - `PermissionsManager::deleteRole('ROLE_STRING_ID');`
- Permission
    - `PermissionsManager::createPermission('PERMISSION_STRING_ID', 'PERMISSION_DESCRIPTION');`
    - `PermissionsManager::deletePermission('PERMISSION_STRING_ID');`
- Attaching and Detaching roles and permissions
    - `PermissionsManager::attach('ROLES_LIST', 'PERMISSIONS_LIST');`
    - `PermissionsManager::detach('ROLES_LIST', 'PERMISSIONS_LIST');`

    
    
    
    
    
    
    
    
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

# Packagist
View this package on Packagist.org: [kduma/permissions](https://packagist.org/packages/kduma/permissions)