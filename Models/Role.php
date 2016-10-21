<?php

namespace KDuma\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['str_id', 'name'];

    public function users()
    {
        return $this->belongsToMany(config('permissions.models.User'))->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(config('permissions.models.Permission'))->withTimestamps();
    }
}
