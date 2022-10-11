<?php

namespace Modules\ProductAttribute\Entities;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;
use Modules\Order\Entities\Order;
use Modules\Cart\Entities\Cart;

class ProductArrayAttribute extends Model
{

    protected $table= "product_array_attributes";
    /**
  * The attributes that are mass assignable.
  *
  * @var array
  */

  protected $fillable = [
     'product_id',
     'attributes',
     'quantity',
     'counter_discount',
     'original_price',
     'price_after_discount',
     'price_discount_ends',
     'sku',
     'weight',
     'barcode',
     'status'
 ];
     protected $casts=[
        "attributes"=>"json"
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
    
    
    public function product(){
        return $this->belongsTo(Product::class);
    }   
    public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }
    public function carts(){
        return $this->belongsToMany(Cart::class,'product_cart','product_array_attribute_id','cart_id')->withPivot(['quantity']);
    }
        public function orders(){
        return $this->belongsToMany(Order::class,'product_order','product_array_attribute_id','order_id');
    }


}
