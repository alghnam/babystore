<?php

namespace Modules\Auth\Entities\Traits\Relationships;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Trait UserRelationships.
 */
class User extends Authenticatable {
    /**
     * @return mixed
    */
    public function roles(){
        return $this->belongsToMany(Role::class,'role_user','user_id','role_id');
    }
    public function permissions(){
        return $this->belongsToMany(Permission::class,'permission_user','user_id','permission_id');
    }
}