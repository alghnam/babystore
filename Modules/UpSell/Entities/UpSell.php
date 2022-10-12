<?php

namespace Modules\UpSell\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UpSell extends Model
{
    use SoftDeletes;

 protected $table='up_sellss';
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = [
        'id',
        'name',
        'product_id',
        'upsells',
        'description',
        'footer'
    ];    
         protected $casts=[
        "upsells"=>"json"
        ];

    
    
   
    // public function upsell(){
    //     return $this->belongsTo("Modules\Product\Entities\Product",'upsells');
    // }
        public function product(){
        return $this->belongsTo("Modules\Product\Entities\Product");
    }
    
}
