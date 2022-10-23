<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Image;

class Category extends Model
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
        'locale',
        'name',
        'parent_id',
        'description',
        'status'
        ];
    public $guarded = [];
    
        
    
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
    
    
    public function products(){
        return $this->hasMany("Modules\Product\Entities\Product");
    }
        public function mainCategory(){
        return $this->belongsTo("Modules\Category\Entities\Category",'parent_id');
    }
    public function subCategories(){
        return $this->hasMany("Modules\Category\Entities\Category",'parent_id','id');
    }
        public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }
}
