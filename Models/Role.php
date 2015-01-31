<?php namespace KDuma\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $fillable = ['str_id','name'];

    public function Users()
    {
        return $this->belongsToMany('\App\User')->withTimestamps();
    }

    public function Permissions()
    {
        return $this->belongsToMany('\KDuma\Permissions\Models\Permission')->withTimestamps();
    }

//    public function members()
//    {
//        return $this->hasMany('\App\Member');
//    }
}
