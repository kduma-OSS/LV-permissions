<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Root permission name
    |--------------------------------------------------------------------------
    |
    | TODO: Write description
    |
    */

    'root_permission' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | User permissions cache time in minutes (default: 60)
    |--------------------------------------------------------------------------
    |
    | Set to null, to disable caching.
    |
    */

    'cache_time' => 60,

    /*
    |--------------------------------------------------------------------------
    | model's name's
    |--------------------------------------------------------------------------
    |
    | If you want you can extend those models and set your models here.
    |
    */

    'models' => [
        'User'          => '\App\User',
        'Role'          => '\KDuma\Permissions\Models\Role',
        'Permission'    => '\KDuma\Permissions\Models\Permission',
    ],




];
