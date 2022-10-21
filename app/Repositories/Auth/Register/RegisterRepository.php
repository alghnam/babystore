<?php
namespace App\Repositories\Auth\Register;

use App\GeneralClasses\MediaClass;
use App\Repositories\Auth\Sms\SmsRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Entities\Cart;

class RegisterRepository extends EloquentRepository implements RegisterRepositoryInterface{
        
    /**
     * @var SmsRepository
     */
    protected $smsRepo;
    public function __construct(SmsRepository $smsRepo){
        $this->smsRepo = $smsRepo;
    }
    public function register($request,$model){//this step to send code into phone_no just
        $data=$request->validated();

        $data['password']=Hash::make($request->password);
        $enteredData=  Arr::except($data ,['image']);
        $enteredData['status']=0;
       $user= $model->create($enteredData);
            $user->roles()->attach([3]);//this user has role : user
        // if(!empty($data['roles'])){
        // }
        //dd($data['image']);
        if(!empty($data['image'])){
            if($request->hasFile('image')){
                $file_path_original_image_user= MediaClass::store($request->file('image'),'profile-images');//store profile image
                $data['image']=$file_path_original_image_user;
            }else{
                $data['image']=$user->image;
            }
            $user->image()->create(['url'=>$data['image'],'imageable_id'=>$user->id,'imageable_type'=>'Modules\Auth\Entities\User']);
        }
        // dd($user);
         $user=$model->where('id',$user->id)->with('image')->first();
            //          if(!empty(Storage::get('session_id'))){
            //     //get all data from all tables have this session -> to store this data for this user instead session
            //     $cart=Cart::where(['session_id'=>Storage::get('session_id')])->first();
            //     // dd($cart);
            //     $cart->user_id=$user->id;
            //     $cart->save();
                
            // }
            
                //بعد التاكد من الريجستر تمام واللوجين متلا بيتم انشاء كل الاسياء اللي بدي استخدمها بالستوريج 
                // $this->putStorage();
         return $user;
    

    }
    
    public function checkCode($request,$model,$rand){
                          $phone_no_reg= Storage::get($rand.'-phone_no_reg');

        // find the code
        $registerCodeNum = $model->firstWhere(['code'=> $request->code,'phone_no'=>$phone_no_reg]);
  
        // check if it does not expired: the time is one hour
        if(!$registerCodeNum){

        // return __('code is invalid, try again');
        return 'هذا الكود غير صالح حاول مرة اخرى';
            }
        if ($registerCodeNum->created_at > now()->addHour()) {
            $registerCodeNum->delete();

        // return __('code is expire');
        return 'انتهت صلاحية هذا الكود';
            }
        return $registerCodeNum;
  
        
   }
   
               public function resendCode($model,$rand){
                $phone_no_reg= Storage::get($rand.'-phone_no_reg');

                  if($phone_no_reg==null){
                      return 'يجب عليك كتابة رقم الموبايل قبل المجي هنا';
                
                  }
             // Delete all old code that user send before.
                $model->where('phone_no', $phone_no_reg)->delete();
            $code=mt_rand(1000, 9999);
    
            //insert code 
            $model->insert(['code'=>$code,'phone_no'=>$phone_no_reg]);
             // Send sms to phone
            // $this->smsRepo->send($code,$phone_no_reg);
           return $code;
         
    }



}