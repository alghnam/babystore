<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\PasswordReset;
use Modules\Auth\Entities\User;
use App\Repositories\Auth\Password\PasswordRepository;
use App\Repositories\Auth\Sms\SmsRepository;
use Illuminate\Support\Facades\Storage;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    /**
     * @var User
     */
    protected $user;
    /**
     * @var PasswordReset
     */
    protected $passwordReset;
    /**
     * @var PasswordRepository
     */
    protected $passwordRep;
    
    /**
     * @var SmsRepository
     */
    protected $smsRepo;
    public function __construct(User $user,PasswordReset $passwordReset,PasswordRepository $passwordRep){
        $this->user = $user;
        $this->passwordRep = $passwordRep;
        $this->passwordReset = $passwordReset;
    }

    // Sends code into Email,phone to make PasswordReset 
    public function __invoke(ForgotPasswordRequest $request)
    {
            $forgotPassword=$this->passwordRep->forgotPassword($request,$this->user,$this->passwordReset);
                         $code=   Storage::get($forgotPassword.'-code');

            if(is_string($forgotPassword)){
            return response()->json(['status'=>false,'message'=>$forgotPassword],400);
        }
                $data=[
                    'code'=>$code,
                    'rand'=>$forgotPassword
                    ];
                return response()->json([
                    'status'=>true,
                    'message' => 'code has been sent into your phone',
                    'data'=> $data
                ]);
    
         
        
    }
}
