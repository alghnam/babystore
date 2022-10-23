<?php

namespace  App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckCodeRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\RegisterCodeNum;
use Modules\Auth\Entities\User;
use App\Repositories\Auth\Register\RegisterRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Auth\Sms\SmsRepository;
class RegisterController extends Controller
{
    /**
     * @var RegisterRepository
     */
    protected $regRepo;
    
        /**
     * @var SmsRepository
     */
    protected $smsRepo;
    
    /**
     * @var User
     */
    protected $user;    
    /**
    * @var RegisterCodeNum
    */
   protected $registerCodeNum;

    

    public function __construct(RegisterRepository $regRepo,User $user,RegisterCodeNum $registerCodeNum, SmsRepository $smsRepo){
        $this->regRepo = $regRepo;
        $this->user = $user;
        $this->registerCodeNum=$registerCodeNum;
        $this->smsRepo=$smsRepo;
        

    }
    
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterRequest $request)
    {
       // try{
          $regUser =  $this->regRepo->register($request, $this->user);
            if(!empty($regUser)){
                $data=$request->validated();
                    // Delete all old code that user send before.
                    RegisterCodeNum::where('phone_no', $data['phone_no'])->delete();
                $code=mt_rand(1000, 9999);
                 $phone_no=$data['phone_no'];
                 $rand=mt_rand();

               Storage::put($rand.'-phone_no_reg',$phone_no);
              

                //insert code 
                RegisterCodeNum::insert(['code'=>$code,'phone_no'=>$phone_no]);
                 // Send sms to phone
                // $this->smsRepo->send($code,$phone_no);
               if($regUser->image){
               $image= $regUser->image->url;
               }else{
               $image= $regUser->image;
               }
               
                $accessToken=$regUser->createToken('token')->accessToken;
                //بعد التاكد من الريجستر تمام واللوجين متلا بيتم انشاء كل الاسياء اللي بدي استخدمها بالستوريج 
                // $this->putStorage();

                $data=[
                    'code'=>$code,
                    'user'=>$regUser,
                     'image'=>$image,
                        'token'=>$accessToken,
                        'rand'=>$rand
                    ];
                    
                return response()->json([
                    'status'=>true,
                    'message'=>'registered successfully',
                    'data'=> $data
                    ]);
            }
        // }catch(\Exception $ex){
        //     return response()->json([
        //         'status'=>500,
        //         'message'=>'There is something wrong, please try again'
        //     ]);  
        // } 
    }

    public function checkCodeRegister(CheckCodeRequest $request,$rand){
        $code=$this->regRepo->checkCode($request,$this->registerCodeNum,$rand);
        if(is_string($code)){
            return response()->json(['status'=>false,'message'=>$code],400);
        }
            $user=User::where('phone_no',$code->phone_no)->first();

            $data=[
                 'user'=> $user->load('image'),
                 'code'=>$code->code
            ];
            return response()->json([
                'status'=>true,
                'message' => 'code is valid , welcome into our website',
                'data'=> $data
            ]);
        
    }
    
        public function resendCodeRegister($rand){
                  $resendCode=$this->regRepo->resendCode($this->registerCodeNum,$rand);
            if(is_string($resendCode)){
            return response()->json(['status'=>false,'message'=>$resendCode],400);
        }
                                  $phone_no_reg= Storage::get($rand.'-phone_no_reg');

       $data=[
                'code'=>$resendCode,
                'phone'=>$phone_no_reg
            ];
            return response()->json([
                'status'=>true,
                'message' => 'code has been sent again successfully',
                'data'=> $data
            ]);
    }


}
