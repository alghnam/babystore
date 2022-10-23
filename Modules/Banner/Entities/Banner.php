<?php

namespace Modules\Banner\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;
        protected $appends = ['original_status'];

    
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'title',
        'description',
        'url',
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
    
        public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }
            public function product(){
        return $this->belongsTo(Product::class);
    }
}
