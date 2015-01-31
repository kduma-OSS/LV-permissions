# L5-permissions


# Set up
in your `User` model (`app/User.php`) add following line:

    use \KDuma\Permissions\Permissions;



## Controller Protect Helper
Add following line in your base controller (`app/Http/Controllers/Controller.php`):

    use \KDuma\Permissions\ProtectTrait;
    
In controller you can use `protect` method. 

    $this->protect('permission');
    
or
    
    $this->protect(['list', 'of', 'required', 'permission']);