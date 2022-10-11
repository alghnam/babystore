<?php

namespace Modules\Seo\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoImage extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seo_id',
        'filename'
    ];
    public function seo(){
        return $this->belongsTo("Modules\Seo\Entities\Seo");
    }
}
