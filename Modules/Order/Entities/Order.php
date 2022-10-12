<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\Cart\Entities\Cart;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payment\Entities\Payment;
use Modules\Coupon\Entities\Coupon;
use Modules\Service\Entities\Service;
use Modules\Product\Entities\Product;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use Modules\Order\Entities\ReviewOrder;
class Order extends Model
{    
    use SoftDeletes;
        protected $appends = ['original_status'];

        /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'order_num',
        'address_id',
        'user_id',
        'products_count', //هنا بخزن فيه لما اخزن البرودكتز بالكارت الوحدة بخزن بهادا العمود عدد المنتجات اللي بهادي الكارت 
        'service_id',
        'payment_id',
        'discount_coupon',
        'price',
        'status'
    ];

    // public function getDeliveryAttribute($value){
    //     if($value==0){
    //         return 'Two Day';
    //     }elseif ($value==1) {
    //         return 'A week';
    //     }
    // }
    // public function getOriginalDeliveryAttribute($value){
    //    return  $this->attributes['delivery'];
    // }
    
    public function getStatusAttribute(){
        return  $this->attributes['status'];
    }
    public function getOriginalStatusAttribute(){
        $value=$this->attributes['status'];
        if($value==1){//قيد التجهيز 
            return 'Being processed';
        }elseif ($value==2) {// قيد الشحن 
            return 'Shipping';
        }elseif ($value==3) {//تم التسليم   
            return 'sent delivered handed';
        }elseif ($value==0) {//معلق   
            return 'hanging';
        }elseif ($value==-1) {//ملغي   
            return 'canceled';
        }
    }
    
    // public function cart(){
    //     return $this->belongsTo(Cart::class);
    // }
    public function coupon(){
        return $this->hasOne(Coupon::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    } 

            public function productArrayAttributes(){
        return $this->belongsToMany(ProductArrayAttribute::class,'product_array_attribute_order','order_id','product_array_attribute_id')->withPivot('quantity');
    }
    // public function addresses(){
    //     return $this->belongsToMany('Modules\Order\Entities\Address','address_order','order_id','address_id');
    // }
    public function address(){
        return $this->belongsTo('Modules\Order\Entities\Address');

    }
                public function reviewOrder(){
        return $this->hasOne(ReviewOrder::class);
    }
            public function couponcode(){
        return $this->hasOne(Coupon::class);
    }
        public function payment(){
        return $this->belongsTo(Payment::class);
    } 
    public function service(){
        return $this->belongsTo(Service::class);
    }
}
