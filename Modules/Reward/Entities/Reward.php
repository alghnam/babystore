<?php

namespace Modules\Reward\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{

        use SoftDeletes;
        protected $appends = ['original_status'];

    /**
  * The attributes that are mass assignable.
  *
  * @var array
  */

  protected $fillable = [
     'value',
     'code',
     'status'
 ];
     public function getStatusAttribute(){
       return  $this->attributes['status'];
    }
    public function getOriginalStatusAttribute(){
        $value=$this->attributes['status'];
        if($value==0){
            return 'Not Used';
        }elseif ($value==1) {
            return 'Replaced';
        }elseif ($value==-1) {
            return 'Expired';
        }
    }
}
