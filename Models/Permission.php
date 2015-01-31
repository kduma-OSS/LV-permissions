<?php namespace KDuma\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    protected $fillable = ['str_id','name'];

    public function roles()
    {
        return $this->belongsToMany(config('permissions.models.Role'))->withTimestamps();
    }

}
