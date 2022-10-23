<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    
    
    public function messages(){
        return $this->hasMany('Modules\Chat\Entities\ChatMessage');
    }
    
}
