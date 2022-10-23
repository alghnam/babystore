<?php

namespace Modules\Search\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Auth\Entities\User;

class Search extends Model
{
        use SoftDeletes;
        protected $appends = ['original_status'];

 /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'word',
        'user_id',
        'session_id',
        'status'
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
        public function user(){
        return $this->belongsTo(User::class);
    }
    
}
