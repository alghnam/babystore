<?php

namespace Modules\Auth\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\Role\StoreRoleRequest;
use Modules\Auth\Http\Requests\Role\UpdateRoleRequest;
use Modules\Auth\Http\Requests\Role\DeleteRoleRequest;
use Modules\Auth\Repositories\Role\RoleRepository;
use Modules\Auth\Entities\Role;
use Modules\Auth\Entities\User;
use Modules\Auth\Entities\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * @var RoleRepository
     */
    protected $roleRepo;

    /**
     * @var Role
     */
    protected $role;
    
    /**
     * @var User
     */
    protected $user;
        /**
     * @var Permission
     */
    protected $permission;

    
    /**
     * rolesController constructor.
     *
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roleRepo, Role $role,User $user,Permission $permission)
    {
        $this->middleware(['permission:roles_read'])->only(['index','getAllPaginates']);
        $this->middleware(['permission:roles_trash'])->only('trash');
        $this->middleware(['permission:roles_restore'])->only('restore');
        $this->middleware(['permission:roles_restore-all'])->only('restore-all');
        $this->middleware(['permission:roles_show'])->only('show');
        $this->middleware(['permission:roles_store'])->only('store');
        $this->middleware(['permission:roles_update'])->only('update');
        $this->middleware(['permission:roles_destroy'])->only('destroy');
        $this->middleware(['permission:roles_destroy-force'])->only('destroy-force');
        $this->roleRepo = $roleRepo;
        $this->role = $role;
        $this->user = $user;
        $this->permission = $permission;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
         try{
        $roles=$this->roleRepo->all($this->role);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$roles],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllPaginates(Request $request){
        
                 try{
        $roles=$this->roleRepo->getAllPaginates($this->role,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$roles],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function trash(Request $request){
                 try{
        $roles=$this->roleRepo->trash($this->role,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$roles],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
    public function rolesUserByName($userId){
        
       $roles=  $this->roleRepo->rolesUserByNameModel($this->user,$userId);
       return $roles;
    }
        public function rolesPermissionByName($permissionId){
                try{
       $roles=  $this->roleRepo->rolesPermissionByName($this->permission,$permissionId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$roles],200);

        
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
    public function store(StoreRoleRequest $request)
    {
        try{
       $role =  $this->roleRepo->store($request,$this->role);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$role->load('permissions')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
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
        $role=$this->roleRepo->find($id,$this->role);
           if(is_string($role)){
            return response()->json(['status'=>false,'message'=>$role],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$role],200);

        
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
    public function update(UpdateRoleRequest $request,$id)
    {
                 try{
       $role= $this->roleRepo->update($request,$id,$this->role);
                  if(is_string($role)){
            return response()->json(['status'=>false,'message'=>$role],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$role->load('permissions')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
       

    }

    //methods for restoring
    public function restore($id){
                 try{
        
        $role =  $this->roleRepo->restore($id,$this->role);
                   if(is_string($role)){
            return response()->json(['status'=>false,'message'=>$role],404);
        }
        
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$role],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        

    }
    public function restoreAll(){
                 try{
        $roles =  $this->roleRepo->restoreAll($this->role);
           if(is_string($roles)){
            return response()->json(['status'=>false,'message'=>$roles],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$roles],200);

        
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
    public function destroy(DeleteRoleRequest $request,$id)
    {
                 try{
       $role= $this->roleRepo->destroy($id,$this->role);
                  if(is_string($role)){
            return response()->json(['status'=>false,'message'=>$role],404);
        }
        
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$role],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function forceDelete(DeleteRoleRequest $request,$id)
    {
                 try{
        //to make force destroy for a Role must be this Role  not found in roles table  , must be found in trash roles
        $role=$this->roleRepo->forceDelete($id,$this->role);
                   if(is_string($role)){
            return response()->json(['status'=>false,'message'=>$role],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$role],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
           
      
    }
}
