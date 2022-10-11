<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\Geocode\Entities\Country;
use Modules\Geocode\Entities\City;
use Modules\Geocode\Entities\Town;
class Address extends Model
{
            /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'country_id', 
        'city_id', 
        'town_id', 
        'latitude', 
        'longitute', 
        'piece_number', 
        'street_number',
        'jada_number', 
        'home_no', 
        'phone_no', 
        'floor_number', 
        'apartment_number', 
        'additional_tips', 
        'default_address', 
        'confirmed'
    ];
    // public function order(){
    //     return $this->belongsTo('Modules\Order\Entities\Order');

    // }
    
    public function orders(){
        return $this->belongsToMany('Modules\Order\Entities\Order','address_order','address_id','order_id');
    }
            public function users(){
        return $this->belongsToMany(User::class,'user_address','address_id','user_id');
        // return $this->belongsToMany(User::class,'user_address');
    }
    // public function user(){
    //     return $this->belongsTo(User::class);

    // }
        public function country(){
        return $this->belongsTo(Country::class);

    }
        public function city(){
        return $this->belongsTo(City::class);

    }
        public function town(){
        return $this->belongsTo(Town::class);

    }
        public function getConfirmedAttribute($value){
        if($value==0){
            return 'Not Confirmed';
        }elseif ($value==1) {
            return 'Confirmed';
        }
    }
    public function getOriginalConfirmedAttribute($value){
       return  $this->attributes['confirmed'];
    }
    
}
