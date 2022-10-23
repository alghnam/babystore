<?php

namespace App\Http\Controllers\API\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\Auth\Login\LoginRepository;
use Illuminate\Http\Request;

use Modules\Auth\Entities\User;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{
    /**
     * @var LoginRepository
     */
    protected $loginRepo;
    public function __construct(LoginRepository $loginRepo){
        $this->loginRepo = $loginRepo;
    }

    public function authLogin(){
        return response()->json([
            'status'=>false,
            'code' => 401,
            'message' => 'You havent authorization in this website',
            'data'=> null
        ]);
    }
    public function userToken(){
        $user=auth('api')->user();
        $user->with('roles')->get();
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'user token',
            'data'=> auth('api')->user()
        ]);
    }
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */


    public function login(LoginRequest $request){
        $loginUser=  $this->loginRepo->login($request);
        $data=$request->validated();
        // dd($data);
        if(is_string($loginUser)){
            return response()->json(['status'=>false,'message'=>$loginUser],400);
        }
                                      $accessToken=auth()->user()->createToken('token')->accessToken;
        $user=auth()->user();
                                   Storage::put($user->id.'-token',$accessToken);
                                           Storage::put($user->id.'-loggedInPassword',$data['password']);

          $userD =  User::where('id',auth()->user()->id)->first();
          
            $data=[
                "token"=>$accessToken,
                "user"=>$userD->load('image')
                ];
            return response()->json([
                    'status'=>true,
                    'message'=>'logged in successfully',
                    'data'=>$data
                ]);
        
      }
       
        
        
    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // try{
      $logout=  $this->loginRepo->logout($request);
        if($logout==true){
            return response()->json([
                'status'=>true,
                'message'=>'logout successfully',
                'data'=>null
            ]);
        }
        // }catch(\Exception $ex){
        //    return response()->json([
        //        'status'=>500,
        //        'message'=>'There is something wrong, please try again'
        //    ]);  
        // } 
    }
}
