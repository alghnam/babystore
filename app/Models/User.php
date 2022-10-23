<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Auth\Entities\Role;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use App\Models\PassportImage;
use Modules\AccountRecovery\Entities\AccountRecovery;
use Modules\Wallet\Entities\Wallet;

use Modules\SystemReview\Entities\SystemReviewType;
class User extends Authenticatable
{

    use LaratrustUserTrait,HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'locale',
        'personal_id',
        'phone_no',
        'passport_num',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function roles(){
        return $this->belongsToMany(Role::class,'role_user','user_id','role_id');
    }
    public function permission(){
        return $this->belongsToMany(Permission::class,'permission_user','user_id','permission_id');
    }
    public function profile(){
        return $this->hasOne("Modules\Profile\Entities\Profile");
    }
    public function passportImages(){
        return $this->hasMany(PassportImage::class);
    }
    public function accountRecoveries(){
        return $this->hasMany(AccountRecovery::class);
    }
    public function image(){
        return $this->hasOne(Image::class);
    }

    public function wallet(){
        return $this->hasOne(Wallet::class);
    }
        public function systemReviewType(){
        return $this->hasOne(SystemReviewType::class);
    }
}
