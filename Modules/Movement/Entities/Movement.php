<?php

namespace Modules\Movement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payment\Entities\Payment;
use Modules\Wallet\Entities\Wallet;

class Movement extends Model
{
    use SoftDeletes;    
        protected $appends = ['original_status','original_type'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet_id',
        'name',
        'value',
        'status'
    ];

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }
    public function payment(){
        return $this->belongsTo(Payment::class);
    }
        public function getTypeAttribute(){
       return  $this->attributes['type'];
    }
    public function getOriginalTypeAttribute(){
        $value=$this->attributes['type'];
        if($value==0){
            return 'Acquired';//مكتسبة
        }elseif ($value==1) {
            return 'Replaced';//استبدال
        }elseif ($value==2) {
            return 'Deposition';//ايداع او شحن
        }elseif ($value==3) {
            return 'Buying';// شراء
        }
    }
        public function getStatusAttribute(){
       return  $this->attributes['status'];
    }
    public function getOriginalStatusAttribute(){
        $value=$this->attributes['status'];
        if($value==0){//
            return 'Pending';
        }elseif ($value==1) {//اي تم عمل هده الحركة 
            return 'finished';
        }
    }
}
