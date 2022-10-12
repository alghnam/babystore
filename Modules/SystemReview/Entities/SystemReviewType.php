<?php

namespace Modules\SystemReview\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\SystemReview\Entities\SystemReview;
class SystemReviewType extends Model
{
        use SoftDeletes;
        protected $appends = ['original_status'];
           /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'name',

                'status',

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
    
    

            public function systemReviews(){
        return $this->hasMany(SystemReview::class);
    }
}
