<?php

namespace Modules\View\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Auth\Entities\User;
use Modules\Product\Entities\Product;
class View extends Model
{
use SoftDeletes;

       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'view_at',
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
        return $this->belongsTo(Product::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
