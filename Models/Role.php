<?php namespace KDuma\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $fillable = ['str_id','name'];

    public function Users()
    {
        return $this->belongsToMany(config('permissions.models.User'))->withTimestamps();
    }

    public function Permissions()
    {
        return $this->belongsToMany(config('permissions.models.Permission'))->withTimestamps();
    }

//    public function members()
//    {
//        return $this->hasMany('\App\Member');
//    }
}
