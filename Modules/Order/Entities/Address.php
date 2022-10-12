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
    
        protected $appends = ['original_confirmed'];
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

    public function orders(){
        return $this->belongsToMany('Modules\Order\Entities\Order','address_order','address_id','order_id');
    }
            public function user(){
        return $this->belongsTo(User::class);
    }

        public function country(){
        return $this->belongsTo(Country::class);

    }
        public function city(){
        return $this->belongsTo(City::class);

    }
        public function town(){
        return $this->belongsTo(Town::class);

    }
        public function getConfirmedAttribute(){
       return  $this->attributes['confirmed'];
    }
    public function getOriginalConfirmedAttribute(){
        $value=$this->attributes['confirmed'];
        if($value==0){
            return 'Not Confirmed';
        }elseif ($value==1) {
            return 'Confirmed';
        }
    }
    
}
