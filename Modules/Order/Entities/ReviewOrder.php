<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Image;

class ReviewOrder extends Model
{
    use SoftDeletes;

    /**
  * The attributes that are mass assignable.
  *
  * @var array
  */

  protected $fillable = [
     'order_id',
     'user_id',
     'rating',
     'description',
     'status'
 ];
    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }
        public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    
        public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }
    
}
