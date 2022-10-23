<?php

namespace Modules\Product\Entities;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Image;
use Modules\SubProduct\Entities\SubProduct;
use Modules\Cart\Entities\Cart;
use Modules\Order\Entities\Order;
use Modules\ProductAttribute\Entities\ProductAttribute;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
// use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Review\Entities\Review;
use Modules\Favorite\Entities\Favorite;
class Product extends Model
{
    use SoftDeletes;
        protected $appends = ['original_status','original_is_offers'];

       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = [
        'id',
        'locale',
        'category_id',
        'sub_category_id',
        'name',
        'description',
        'status',
        'quantity',
        'original_price',
        'price_discount_ends',
        'is_offers',
        'status'
    ];
    

    //     public function productAttributes()
    // {
    //     return $this->belongsToMany(ProductAttribute::class,'product_has_attributes','product_id','attribute_id')
    //         ->withPivot('attribute_value_id');
    //         // ->using('App\ProductAttributes');
    // }
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
    
     public function getIsOffersAttribute(){
        return  $this->attributes['is_offers'];
        
    }
    public function getOriginalIsOffersAttribute(){
        $value=$this->attributes['is_offers'];
        if($value==0){
            return 'No';
        }elseif($value==1) {
            return 'Yes';
        }
    } 
    
    
    
    public function subProducts(){
        return $this->hasMany(SubProduct::class);
    } 
    
    public function productAttributes(){
        return $this->hasMany(ProductAttribute::class);
    }   
    
    public function productArrayAttributes(){
        return $this->hasMany(ProductArrayAttribute::class);
    }
    public function category(){
        return $this->belongsTo("Modules\Category\Entities\Category");
    }
        public function subCategory(){
        return $this->belongsTo("Modules\Category\Entities\SubCategory",'sub_category_id');
    }
        public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }

    public function comments(){
        return $this->hasMany("Modules\Comment\Entities\Comment");
    }
    // public function similarProducts(){
    //     return $this->belongsToMany("Modules\SimilarProduct\Entities\SimilarProduct",'similar_product','product_id','similar');
    // }
    public function similarProducts(){
        return $this->hasMany("Modules\SimilarProduct\Entities\SimilarProduct",'product_id');
    }
        public function upsellsProduct(){
        return $this->hasMany("Modules\UpSell\Entities\UpSell",'upsells');
    }
    

    public function productImages(){
        return $this->hasMany(ProductImage::class);
    }
    public function seos(){
        return $this->hasOne(Seo::class);
    }

    public function coupon(){
        return $this->hasOne(Coupon::class);
    }
    //     public function bills(){
    //     return $this->belongsToMany(Bill::class,'products_bills','product_id','bill_id');
    // }
    public function orders(){
        return $this->belongsToMany(Order::class,'product_order','product_id','order_id');
    }
    
    // public function orders(){
    //     return $this->hasMany(Order::class);
    // }
    public function reviews(){
        return $this->hasMany(Review::class);
    }
        public function favorites(){
        return $this->hasMany(Favorite::class);
    }
// public function avgReviewRating()
// {
//     return $this->reviews()->avg('rating');
// }
    
}
