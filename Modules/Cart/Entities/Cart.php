<?php

namespace Modules\Cart\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\ActiveScope;
class Cart extends Model
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
        'product_id',
        'user_id',
        'status'
    ];
    
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
    
        protected static function boot()

    {

        parent::boot();

        static::addGlobalScope(new ActiveScope);

    }
    
    
        public function productArrayAttributes(){
        return $this->belongsToMany(ProductArrayAttribute::class,'product_cart','cart_id','product_array_attribute_id')->withPivot(['quantity']);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    // public function orders(){
    //     return $this->hasMany(Order::class);
    // }
}
