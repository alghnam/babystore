<?php

namespace Modules\Geocode\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Country extends Model 
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
    public function cities(){
        return $this->hasMany("Modules\Geocode\Entities\City");
    }
    public function users(){
        return $this->hasMany("Modules\Geocode\Entities\users");
    }
}
