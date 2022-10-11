<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Order\Entities\Order;
use App\Scopes\ActiveScope;

class Payment extends Model
{
    use SoftDeletes;
   /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'status'
    ];
       public function getTypeAttribute($value){
        if($value==0){
            return 'Public';//ex:via,knet
        }elseif ($value==1) {
            return 'Private';//ex:wallet , upon receipt
        }
    }
    public function getOriginalTypeAttribute($value){
       return  $this->attributes['type'];
    }
        
    
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
    
    
        public function orders(){
        return $this->hasMany(Order::class);
    } 
        protected static function boot(){
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }
}
