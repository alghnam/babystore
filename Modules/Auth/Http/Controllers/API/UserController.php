<?php

namespace Modules\Auth\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\User\StoreUserRequest;
use Modules\Auth\Http\Requests\User\UpdateUserRequest;
use Modules\Auth\Http\Requests\User\DeleteUserRequest;
use Modules\Auth\Repositories\Role\RoleRepository;
use Modules\Auth\Repositories\Permission\PermissionRepository;
use Modules\Geocode\Repositories\Country\CountryRepository;
use Modules\Geocode\Repositories\City\CityRepository;
use Modules\Geocode\Repositories\Town\TownRepository;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Modules\Auth\Repositories\User\UserRepository;
use Modules\Auth\Entities\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var UserRepository
     */
    protected $userRepo;
        /**
     * @var User
     */
    protected $user;
    /**
     * @var RoleRepository
     */
    protected $roleRepo;
    
    /**
     * @var PermissionRepository
     */
    protected $permissionRepo;

    /**
     * @var CountryRepository
     */
    protected $countryRepo;
    /**
     * @var CityRepository
     */
    protected $cityRepo;
    
    /**
     * @var TownRepository
     */
    protected $townRepo;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $users
     */
    public function __construct(BaseRepository $baseRepo, User $user,UserRepository $userRepo, RoleRepository $roleRepo,PermissionRepository $permissionRepo, CountryRepository $countryRepo,CityRepository $cityRepo,TownRepository $townRepo)
    {
        $this->middleware(['permission:users_read'])->only(['index','getAllPaginates','usersCount']);
        $this->middleware(['permission:users_trash'])->only('trash');
        $this->middleware(['permission:users_restore'])->only('restore');
        $this->middleware(['permission:users_restore-all'])->only('restore-all');
        $this->middleware(['permission:users_show'])->only('show');
        $this->middleware(['permission:users_store'])->only('store');
        $this->middleware(['permission:users_update'])->only('update');
        $this->middleware(['permission:users_destroy'])->only('destroy');
        $this->middleware(['permission:users_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->user = $user;
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
        $this->countryRepo = $countryRepo;
        $this->cityRepo = $cityRepo;
        $this->townRepo = $townRepo;
    }
          public function search($word){
            try{
                  $users=$this->userRepo->search($this->user,$word);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$users],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }
     public function countData(){
        $countData=$this->userRepo->countData($this->user);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countData],200);
          
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        try{
          
        $users=$this->userRepo->all($this->user);
        
        
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$users],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllPaginates(Request $request){
        try{
        $users=$this->userRepo->getAllPaginates($this->user,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$users],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function activation($id){
         try{
        $user=  $this->user->find($id);
        $user->status=1;
        $user->save();
         
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$user],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
         
     }


    // methods for trash
    public function trash(Request $request){
 try{
        $users=$this->userRepo->trash($this->user,$request);
        if(is_string($users)){
            return response()->json(['status'=>false,'message'=>$users],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$users],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
       // try{
           $user= $this->userRepo->store($request,$this->user);
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$user->load('roles')],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         try{
        $user=$this->userRepo->find($id,$this->user);
                if(is_string($user)){
            return response()->json(['status'=>false,'message'=>$user],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$user],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
    }

 

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request,$id)
    {
         try{
        $user= $this->userRepo->update($request,$id,$this->user);
          if(is_string($user)){
            return response()->json(['status'=>false,'message'=>$user],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$user->load('roles')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        

    }

    //methods for restoring
    public function restore($id){
        
         try{
        $user =  $this->userRepo->restore($id,$this->user);
         if(is_string($user)){
            return response()->json(['status'=>false,'message'=>$user],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$user],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

           

    }
    public function restoreAll(){
         try{
            $users =  $this->userRepo->restoreAll($this->user);
            if(is_string($users)){
                return response()->json(['status'=>false,'message'=>$users],404);
            }
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$users],200);
    
            
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        

    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteUserRequest $request,$id)
    {
         try{
       $user= $this->userRepo->destroy($id,$this->user);
          if(is_string($user)){
            return response()->json(['status'=>false,'message'=>$user],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$user],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteUserRequest $request,$id)
    {
        //to make force destroy for a user must be this user  not found in users table  , must be found in trash users
        //  try{
        $user=$this->userRepo->forceDelete($id,$this->user);
        if(is_string($user)){
            return response()->json(['status'=>false,'message'=>$user],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$user],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
       
    }
}
