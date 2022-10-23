<?php
namespace App\Repositories\Auth\Password;

use App\Repositories\Auth\Sms\SmsRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PasswordRepository  implements PasswordRepositoryInterface{
        /**
     * @var SmsRepository
     */
    protected $smsRepo;
    public function __construct(SmsRepository $smsRepo){
        $this->smsRepo = $smsRepo;
    }
    public function forgotPassword($request,$model1,$model2){//model2: password_resets , model1: user
        $data=$request->validated();
        $user= $model1->where(['phone_no'=>$data['phone_no']])->first();
        if(!empty($user)){
         
            // Generate random code
            $data['code'] = mt_rand(1000, 9999);
            // Create a new code
            $model2->create($data);
                         $rand=mt_rand();
                Storage::put($rand.'-code',$data['code']);
                Storage::put($rand.'-phone_no',$data['phone_no']);


            // Send sms to phone
           // $this->smsRepo->send($data['code'],$user->phone_no);
            return $rand;
        }else{
            // return __('phone_no that you wrote it , not exsit in system');
            return 'رقم الموبايل الذي كتبته غير موجود بالنظام';
        }
        
    }
    public function checkCode($request,$model,$rand){
               $phone_no=  Storage::get($rand.'-phone_no');


        // find the code
        $passwordReset = $model->firstWhere(['code'=> $request->code,'phone_no'=>$phone_no]);
        // check if it does not expired: the time is one hour
        if($passwordReset==null){
        // return __('code is invalid, try again');
        return 'هذا الكود غير صالح حاول مرة اخرى';
    }

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
        // return __('code is expire');
        return 'انتهت صلاحية هذا الكود';
    
        }
        return $passwordReset;
  
        
         
    }
    


    public function resetPassword($request,$model,$rand){//model :user 
                   $phone_no=  Storage::get($rand.'-phone_no');
        // find user's phone_no 
        $user = $model->firstWhere('phone_no', $phone_no);
        if(empty($user)){

            // return __('phone_no that you wrote it , not exsit in system');
            return 'رقم الموبايل الذي كتبته غير موجود بالنظام';
        }else{
            $user->password=Hash::make($request->password);
            $user->save();    
    
            return 200;

        }
    }


}