<?php

namespace Modules\Order\Entities;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Image;
use Modules\Cart\Entities\Cart;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Modules\ProductAttribute\Entities\ProductAttribute;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
// use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductOrder extends Model
{
    protected $table='product_order';
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = [
        'id',
        'order_id',
        'product_id'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }   
}
