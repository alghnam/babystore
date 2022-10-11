<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class SubCategory extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'locale',
        'category_id',
        'name',
        'description',
        'status'
    ];
    
    
        public $guarded = [];
    
    
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
    
    
        public function category(){
        return $this->belongsTo("Modules\Category\Entities\Category");
    }
        public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }

}
