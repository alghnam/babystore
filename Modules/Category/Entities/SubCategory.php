<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    
        use SoftDeletes;

        protected $appends = ['original_status'];
        protected $table='sub_categoriess';
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
    
        public function category(){
        return $this->belongsTo("Modules\Category\Entities\Category");
    }
        public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }

}
