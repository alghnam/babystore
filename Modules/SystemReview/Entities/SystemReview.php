<?php

namespace Modules\SystemReview\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SystemReview\Entities\SystemReviewType;
use Modules\Auth\Entities\User;
use Illuminate\Database\Eloquent\SoftDeletes;
class SystemReview extends Model
{
    use SoftDeletes;
        protected $appends = ['original_status'];
    
        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'system_review_type_id',
        'user_id',
        'body',
        'name',
        'body',
        'email',
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
    
    
    
        public function systemReviewType(){
        return $this->belongsTo(SystemReviewType::class);
    }
            public function user(){
        return $this->belongsTo(User::class);
    }
    
    
}
