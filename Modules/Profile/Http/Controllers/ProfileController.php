<?php

namespace Modules\Profile\Http\Controllers;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\User;
use Modules\Profile\Http\Requests\AcceptOnRequestDocumentationRequest;
use Modules\Profile\Http\Requests\ShowProfileRequest;
use Modules\Profile\Http\Requests\StoreProfileRequest;
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
                $this->middleware(['permission:profile_store'])->only('store');
                $this->middleware(['permission:profile_show'])->only('show');
                $this->middleware(['permission:profile_request'])->only('requestDocumentation');
                $this->middleware(['permission:profile_accept'])->only('acceptingOnRequestDocumentation');//for admin
                $this->middleware(['permission:profile_update'])->only('update');
                $this->middleware(['permission:profile_update-password'])->only('updatePassword');

        $this->baseRepo = $baseRepo;
        $this->profileRepo = $profileRepo;
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('profile::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('profile::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProfileRequest $request,$id)
    {
        $this->profileRepo->storeImage($request,$id,$this->user);
        return \response()->json([
            'status'=>true,
            'message'=>'stored your image successfully'
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(ShowProfileRequest $request)
    {
        $show=  $this->profileRepo->show($this->user);
        return \response()->json([
            'status'=>true,
            'message'=>'data has been getten successfully',
            'data'=>$show
        ]);


    }

    public function requestDocumentation($userId,AddSecurityCodeRequest $req){
      $requestDocumentation=  $this->profileRepo->requestDocumentation($this->user,$userId,$req);
        if(is_string($requestDocumentation)){
            return response()->json(['status'=>false,'message'=>$requestDocumentation],400);
        }
          return \response()->json([
                'status'=>true,
                'message'=>'your request documentation under reviewing',
                'data'=>$requestDocumentation
            ]);
     
    }

    public function acceptingOnRequestDocumentation(AcceptOnRequestDocumentationRequest $request,$userId){
       $acceptingOnRequestDocumentation= $this->profileRepo->acceptingOnRequestDocumentation($this->user,$userId);
               if(is_string($acceptingOnRequestDocumentation)){
            return response()->json(['status'=>false,'message'=>$acceptingOnRequestDocumentation],400);
        }
           return \response()->json([
                'status'=>true,
                'message'=>'request documentation has been accepted',
                'data'=>$acceptingOnRequestDocumentation
            ]);


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

        return \response()->json([
            'code'=>200,
            'status'=>true,
            'message'=>'updated successfully',
            'data'=>$data
        ]);


    }
    
    public function updatePassword(UpdatePasswordRequest $request){
                 $userId=auth()->guard('api')->user()->id;

      $userUpdatedPassword=  $this->profileRepo->updatePassword($request,$this->user);
        if(is_string($userUpdatedPassword)){
            return response()->json(['status'=>false,'message'=>$userUpdatedPassword],400);
        }
                          $token=Storage::get($userId.'-token');

        //   dd($userUpdatedPassword);
          
              $data=[
                  'user'=>$userUpdatedPassword,
                  'token'=>$token
                  ];
                              return \response()->json([
            'status'=>true,
            'message'=>'updated successfully',
            'data'=>$data
        ]);
          
        
      
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
