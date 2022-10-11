<?php

namespace Modules\Seo\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class Seo extends Model
{
          /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'locale',
        'product_id',
        'slug',
        'meta_description',
        'name',
        'title'
    ];
    
    public function seoImages(){
        return $this->hasMany(SeoImage::class);
    }  
      public function product(){
        return $this->belongsTo(Product::class);
    }
}
