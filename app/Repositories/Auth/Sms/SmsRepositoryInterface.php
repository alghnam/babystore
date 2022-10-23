<?php
namespace App\Repositories\Auth\Sms;

interface SmsRepositoryInterface{
    public function send($code,$phone_no);
}