<?php

namespace Modules\PushNotification\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;

class PushNotification extends Model
{
/**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title',
        'body'
    ];    
    

    
    
        public function users(){
        return $this->belongsToMany(User::class,'push_notification_user','push_notification_id','user_id');
    } 
    
}
