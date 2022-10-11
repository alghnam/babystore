<?php
namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckCodeRequest;
use App\Models\PasswordReset;
use App\Repositories\Auth\Password\PasswordRepository;

class CheckCodeController extends Controller
{
        /**
     * @var PasswordReset
     */
    protected $PasswordReset;
            /**
     * @var PasswordRepository
     */
    protected $passwordRepo;
    public function __construct(PasswordReset $passwordReset,PasswordRepository $passwordRepo){
        $this->passwordRepo = $passwordRepo;
        $this->passwordReset = $passwordReset;

    }
    public function __invoke(CheckCodeRequest $request,$rand)
    {
            $checkCode=$this->passwordRepo->checkCode($request,$this->passwordReset,$rand);
            if(is_string($checkCode)){
            return response()->json(['status'=>false,'message'=>$checkCode],400);
        }
                return response()->json([
                    'status'=>true,
                    'message' => 'Thanks, Code is valid',
                    'data'=> null
                ]);
                
           
        
    }
    
}