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
    public function getStatusAttribute($value){
        if($value==0){
            return 'Not Active';
        }elseif ($value==1) {
            return 'Active';
        }
    }
    public function getOriginalStatusAttribute($value){
       return  $this->attributes['status'];
    }
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }
        public function questionCategory(){
        return $this->belongsTo(QuestionCategory::class);
    }
}
