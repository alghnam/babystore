<?php

namespace Modules\SimilarProduct\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class SimilarProduct extends Model
{
protected $table='similar_product';
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = [
        'id',
        'product_id',
        'similar',
        'status'
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
    
    
    // public function products(){
    //     return $this->belongsToMany("Modules\Product\Entities\Product",'similar_product','similar','product_id');
    // }
    // public function product(){
    //     return $this->belongsToMany("Modules\Product\Entities\Product");
    // } 
    public function similar(){
        return $this->belongsTo("Modules\Product\Entities\Product",'similar');
    }
}
