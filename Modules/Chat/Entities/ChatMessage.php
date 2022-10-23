<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
        use SoftDeletes;

    
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'client_id',
        'user_id',
        'room_chat_id',
        'message'
    ];

    public function room(){
        return $this->belongsTo('Modules\Chat\Entities\ChatRoom','chat_room_id');
    }
    
        public function user(){
        return $this->belongsTo('Modules\Auth\Entities\User','user_id');
    }
            public function client(){
        return $this->belongsTo('Modules\Auth\Entities\User','client_id');
    }
}
