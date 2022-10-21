<?php

namespace Modules\Auth\Entities;

use App\Models\Image;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Auth\Entities\Role;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Cart\Entities\Cart;
use Modules\Favorite\Entities\Favorite;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Address;
use Illuminate\Notifications\Notifiable;
use Modules\PushNotification\Entities\PushNotification;
class User extends Authenticatable
{
    use LaratrustUserTrait,HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
        protected $appends = ['original_status'];


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'locale',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_no',
        'status',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    

    
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [

    ];
    public function roles(){
        return $this->belongsToMany(Role::class,'role_user','user_id','role_id');
    }
    public function permissions(){
        return $this->belongsToMany("Modules\Auth\Entities\Permission",'permission_user','user_id','permission_id');
    }
    public function passportImages(){
        return $this->hasMany('App\Models\PassportImage');
    }
    public function accountRecoveries(){
        return $this->hasMany(AccountRecovery::class);
    }
    public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }
    
    public function country(){
        return $this->belongsTo("Modules\Geocode\Entities\Country");
    }
    public function city(){
        return $this->belongsTo("Modules\Geocode\Entities\City");
    }
    public function town(){
        return $this->belongsTo("Modules\Geocode\Entities\Town");
    }
    
        public function orders(){
        return $this->hasMany('Modules\Order\Entities\Order');
    }
    


    public function addresses(){
        return $this->hasMany(Address::class);

    }
    public function favorites(){
        return $this->hasMany(Favorite::class);
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function cart(){
        return $this->hasOne(Cart::class);
    }
    
    public function pushNotifications(){
        return $this->belongsToMany(PushNotification::class,'push_notification_user','user_id','push_notification_id');
    } 
    public function getStatusAttribute(){
       return  $this->attributes['status'];
    }
    public function getOriginalStatusAttribute(){
        $value=$this->attributes['status'];
        if($value==0){
            return 'InActive';
        }elseif ($value==1) {
            return 'Active';
        }
    }
    
}
