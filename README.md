# L5-permissions


# Setup
Add the package to the require section of your composer.json and run `composer update`

    "kduma/permissions": "1.0.*@dev"

Then add the Service Provider to the providers array in `config/app.php`:

    'KDuma\Permissions\PermissionsServiceProvider',

Then add the Facade to the aliases array in `config/app.php`:

    'Permissions' 	=> 'KDuma\Permissions\Facades',
    
in your `User` model (`app/User.php`) add following line:

    use \KDuma\Permissions\Permissions;
    
Add following line in your base controller (`app/Http/Controllers/Controller.php`):

    use \KDuma\Permissions\ProtectTrait;








## Controller Protect Helper
In controller you can use `protect` method. 

    $this->protect('permission');
    
or
    
    $this->protect(['list', 'of', 'required', 'permission']);