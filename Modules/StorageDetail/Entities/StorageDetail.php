<?php

namespace Modules\StorageDetail\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class StorageDetail extends Model
{
          /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'locale',
        'sku',
        'barcode',
        'wight',
        'product_id'
    ];
    
      public function product(){
        return $this->belongsTo(Product::class);
    }
}
