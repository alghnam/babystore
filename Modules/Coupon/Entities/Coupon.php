<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Order\Entities\Order;

class Coupon extends Model
{
    use SoftDeletes;
        protected $appends = ['original_status','original_is_used'];
           /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'name',
        'value',
        'is_used',
        'order_id',
        'end_date',
        'status'
    ];
        
    

    public function order(){
        return $this->belongsTo(Order::class);
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
    
    public function getIsUsedAttribute(){
        return  $this->attributes['is_used'];
    }
    public function getOriginalIsUsedAttribute(){
        $value=$this->attributes['is_used'];
        if($value==0){
            return 'Not Use';
        }elseif ($value==1) {
            return 'Used';
        }
    } 

    // public function getDiscountAttribute($value){
    //     if($value==0){
    //         return 'precentage';
    //     }elseif ($value==1) {
    //         return 'fixed';
    //     }1
    // }
    // public function getOriginalDiscountAttribute($value){
    //    return  $this->attributes['discount'];
    // } 
    //    public function getPaymentAttribute($value){
    //     if($value==0){
    //         return 'cash';
    //     }
    // }
    // public function getOriginalPaymentAttribute($value){
    //    return  $this->attributes['payment'];
    // }
}
