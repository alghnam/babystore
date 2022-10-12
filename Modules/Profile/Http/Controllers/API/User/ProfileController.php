<?php

namespace Modules\Profile\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\User;
use Modules\Profile\Http\Requests\AcceptOnRequestDocumentationRequest;
use Modules\Profile\Http\Requests\ShowProfileRequest;
use Modules\Profile\Http\Requests\UpdateProfileRequest;
use Modules\Profile\Repositories\ProfileRepository;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Modules\Profile\Http\Requests\AddSecurityCodeRequest;
use  Modules\Profile\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
     /**
     * @var ProfileRepository
     */
    protected $profileRepo;
         /**
     * @var User
     */
    protected $user;
    
    /**
     * ProfileController constructor.
     *
     * @param ProfileRepository $Profile
     */
    public function __construct(BaseRepository $baseRepo, ProfileRepository $profileRepo, User $user)
    {
                $this->middleware(['permission:profile_accept'])->only('acceptingOnRequestDocumentation');//for admin

        $this->baseRepo = $baseRepo;
        $this->profileRepo = $profileRepo;
        $this->user = $user;
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(ShowProfileRequest $request)
    {
        $data=  $this->profileRepo->show($this->user);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data->load('image')],200);



    }

    public function requestDocumentation($userId,AddSecurityCodeRequest $req){
      $requestDocumentation=  $this->profileRepo->requestDocumentation($this->user,$userId,$req);
        if(is_string($requestDocumentation)){
            return response()->json(['status'=>false,'message'=>$requestDocumentation],400);
        }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$requestDocumentation],200);

     
    }

    public function acceptingOnRequestDocumentation(AcceptOnRequestDocumentationRequest $request,$userId){
       $acceptingOnRequestDocumentation= $this->profileRepo->acceptingOnRequestDocumentation($this->user,$userId);
               if(is_string($acceptingOnRequestDocumentation)){
            return response()->json(['status'=>false,'message'=>$acceptingOnRequestDocumentation],400);
        }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$acceptingOnRequestDocumentation],200);



    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateProfileRequest $request)
    {   
        $userId=auth()->guard('api')->user()->id;

      $userUpdated=  $this->profileRepo->update($request,$userId,$this->user);
                          $token=Storage::get($userId.'-token');
                          $image=$userUpdated->image;
                          $data=[
                              'user'=>$userUpdated,
                              'token'=>$token,
                              'image'=>$image
                              ];

                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);



    }
    
    public function updatePassword(UpdatePasswordRequest $request){
                 $userId=auth()->guard('api')->user()->id;

      $userUpdatedPassword=  $this->profileRepo->updatePassword($request,$this->user);
        if(is_string($userUpdatedPassword)){
            return response()->json(['status'=>false,'message'=>$userUpdatedPassword],400);
        }
                          $token=Storage::get($userId.'-token');


              $data=[
                  'user'=>$userUpdatedPassword,
                  'token'=>$token
                  ];
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

          
        
      
    }


}
