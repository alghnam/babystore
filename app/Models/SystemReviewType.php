<?php

namespace Modules\SystemReview\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SystemReview\Entities\SystemReview;
class SystemReviewType extends Model
{
    protected $table="system_review_types";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];
    
        public function systemReviews(){
        return $this->hasMany(SystemReview::class);
    }

    
}
