<?php
namespace App\Repositories\Auth\Sms;

use Nexmo\Laravel\Facade\Nexmo;


class SmsRepository  implements SmsRepositoryInterface{
    public function send($code,$phone_no){
        Nexmo::message()->send([
            'to'=>"{$phone_no}",
            'from'=>'sender',
            'text'=>'your code : '.$code
        ]);
    }
}
