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
        protected $appends = ['original_status','original_type'];
   /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'type',
        'status'
    ];
       public function getTypeAttribute(){
       return  $this->attributes['type'];
    }
    public function getOriginalTypeAttribute(){
        $value=$this->attributes['type'];
        if($value==0){
            return 'Public';//ex: other payment: via,knet
        }elseif ($value==1) {
            return 'Private';//ex:wallet , upon receipt
        }
    }
        
      public function getStatusAttribute(){
        return  $this->attributes['status'];
        
    }
    public function getOriginalStatusAttribute(){
        $value=$this->attributes['status'];
        if($value==0){
            return 'InActive';
        }elseif($value==1) {
            return 'Active';
        }
    } 
    
        public function orders(){
        return $this->hasMany(Order::class);
    } 
        protected static function boot(){
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }
}
