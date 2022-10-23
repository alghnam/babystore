<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\Order;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
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
        'period',
        'value',
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
    
    
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
