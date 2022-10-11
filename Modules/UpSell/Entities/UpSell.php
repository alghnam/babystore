<?php

namespace Modules\UpSell\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UpSell extends Model
{
    use SoftDeletes;
 protected $table='up_sells';
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
        'footer',
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
    
    
   
    public function product(){
        return $this->belongsTo("Modules\UpSell\Entities\UpSell",'upsells');
    }
    
}
