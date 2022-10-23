<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\AccountRecovery\Entities\AccountRecovery;

class PassportImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'filename'
    ];
    public function user(){
        return $this->belongsTo("Modules\Auth\Entities\User");
    }
    public function accountRecovery(){
        return $this->belongsTo(AccountRecovery::class);
    }
}
