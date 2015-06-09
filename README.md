# L5-permissions
[![Latest Stable Version](https://poser.pugx.org/kduma/permissions/v/stable.svg)](https://packagist.org/packages/kduma/permissions) 
[![Total Downloads](https://poser.pugx.org/kduma/permissions/downloads.svg)](https://packagist.org/packages/kduma/permissions) 
[![Latest Unstable Version](https://poser.pugx.org/kduma/permissions/v/unstable.svg)](https://packagist.org/packages/kduma/permissions) 
[![License](https://poser.pugx.org/kduma/permissions/license.svg)](https://packagist.org/packages/kduma/permissions)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5babe580-a3a3-41cf-a1b6-3c266f29ef92/mini.png)](https://insight.sensiolabs.com/projects/5babe580-a3a3-41cf-a1b6-3c266f29ef92)

Simple (really?) package to provide Roles and Permissions to Laravel 5.1

# Setup
Add the package to the require section of your composer.json and run `composer update`

    "kduma/permissions": "^1.1"

Then add the Service Provider to the providers array in `config/app.php`:

    KDuma\Permissions\PermissionsServiceProvider::class,

Then add the Facades to the aliases array in `config/app.php`:

    'Permissions'        => KDuma\Permissions\Facades\Permissions::class,
    'PermissionsManager' => KDuma\Permissions\Facades\PermissionsManager::class,
    
Then add the Middleware's to the `routeMiddleware` array in `app/Http/Kernel.php`:

    'permission' => \KDuma\Permissions\Middleware\Permission::class,
    'role' => \KDuma\Permissions\Middleware\Role::class,
    
in your `User` model (`app/User.php`) add following line:

    use \KDuma\Permissions\Permissions;
    
    
Run the following command to copy migrations:

    php artisan vendor:publish --provider="KDuma\Permissions\PermissionsServiceProvider" --tag="migrations"

In `SampleMigrations` folder are examples you can use or make new based on.

- In `2015_01_01_000005_create_and_assign_roles_and_permissions.php` migration file are sample Roles and Permissions
- In `2015_01_01_000006_create_administrator_account.php` migration file is created administrator account

# Usage
## Protecting controllers and routes
You can use two Middleware's: `permission` and `role`

```php
Route::get('admin/profile', [
    'middleware' => 'role:editor',
    'uses' => 'UserController@showProfile'
]);
```
or
   
```php
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user.edit:user.panel');
    }
}
```

You can provide more than one role/permission in one call by separating them using `:`. For example: `permission:perm1:perm2:perm3`. Middleware refuses access if user hasn't any of provided permissions/roles.
    
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

    
## Admin role
As default `admin` role is setted to be root role. Root role always has all permissions. You can change it in configuration file.
    
    
    
    
    
    
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
