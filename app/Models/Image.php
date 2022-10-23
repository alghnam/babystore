<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Profile\Entities\Profile;
use Modules\Auth\Entities\User;
use Modules\Order\Entities\ReviewOrder;
use Modules\ProductAttribute\Entities\ProductAttribute;
class Image extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'imageable_id',
        'imageable_type'
    ];

    /**
     * Get the parent imageable model (user or any table need t image).
     */
    // public function imageable()
    // {
    //     return $this->morphTo(User::class,'imageable_type','imageable_id');
    // }
        /**
     * Get the parent imageable model (user or any table need t image).
     */
    // public function imageable()
    // {
    //     return $this->morphTo(ReviewOrder::class,'imageable_type','imageable_id');
    // }
    
      public function imageable()
    {
        return $this->morphTo(ProductAttribute::class,'imageable_type','imageable_id');
    }
}

