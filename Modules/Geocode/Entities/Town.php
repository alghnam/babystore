<?php

namespace Modules\Geocode\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Town extends Model 
{
    use SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'code',
        'city_id',
        'status'
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
    public $guarded = [];
    public function city(){
        return $this->belongsTo("Modules\Geocode\Entities\City");
    }
    public function users(){
        return $this->hasMany("Modules\Geocode\Entities\users");
    }
}
