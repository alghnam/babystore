<?php

namespace Modules\Question\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Scopes\ActiveScope;
use Modules\Question\Entities\QuestionCategory;

class Question extends Model
{
    
  use SoftDeletes;
        protected $appends = ['original_status'];
  protected $table='questions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'locale',
        'question',
        'question_category_id',
        'answer',
        'status',
        'deleted_at',
        'created_at'
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
    
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }
        public function questionCategory(){
        return $this->belongsTo(QuestionCategory::class);
    }
}
