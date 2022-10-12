<?php

namespace Modules\ProductAttribute\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;
use App\Models\Image;
class ProductAttribute extends Model
{
    use SoftDeletes;
    protected $table= "attributes";
    /**
  * The attributes that are mass assignable.
  *
  * @var array
  */

  protected $fillable = [
     'product_id',
     'option',
     'attributes',
     'status'
 ];
     
    
 
    
    
    
         public function mainCategory(){
        return $this->belongsTo("Modules\Category\Entities\Category",'parent_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
        public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }
}
