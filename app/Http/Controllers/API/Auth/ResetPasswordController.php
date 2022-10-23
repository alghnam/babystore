<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Modules\Auth\Entities\User;
use App\Repositories\Auth\Password\PasswordRepository;
class ResetPasswordController extends Controller
{
    /**
     * @var PasswordRepository
     */
    protected $passwordRepo;
    public function __construct(User $user,PasswordRepository $passwordRepo){
        $this->user = $user;
        $this->passwordRepo = $passwordRepo;
    }
    public function __invoke(ResetPasswordRequest $request,$rand)
    {
        
            $passwordReset=$this->passwordRepo->resetPassword($request,$this->user,$rand);
            if(is_string($passwordReset)){
            return response()->json(['status'=>false,'message'=>$passwordReset],400);
        }
                return response()->json([
                    'status'=>true,
                    'message' => 'your request(reset pass) has been successfully , now you make login via entered password',
                    'data'=> null
                ]);

                
    }

}
