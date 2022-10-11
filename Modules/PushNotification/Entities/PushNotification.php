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
    
     public function getStatusAttribute($value){
        if($value==0){
            return 'InActive';
        }elseif ($value==1) {
            return 'Active';
        }
    }
    public function getOriginalStatusAttribute($value){
        return  $this->attributes['status'];
    } 
    
    
        public function users(){
        return $this->belongsToMany(User::class,'push_notification_user','push_notification_id','user_id');
    } 
    
}
