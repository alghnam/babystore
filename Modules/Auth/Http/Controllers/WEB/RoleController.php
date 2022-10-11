<?php

namespace Modules\Auth\Http\Controllers\WEB;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;
use Modules\Auth\Entities\Permission;
use Modules\Auth\Entities\Role;
use Modules\Auth\Http\Requests\Role\DeleteRoleRequest;
use Modules\Auth\Http\Requests\Role\StoreRoleRequest;
use Modules\Auth\Http\Requests\Role\UpdateRoleRequest;
use Modules\Auth\Repositories\Permission\PermissionRepository;
use Modules\Auth\Repositories\Role\RoleRepository;

class RoleController extends Controller
{
    /**
     * @var BaseRepository
     */
    protected $baseRepo;
            /**
     * @var RoleRepository
     */
    protected $roleRepo;

        /**
     * @var Role
     */
    protected $role;
            /**
     * @var Permission
     */
    protected $permission;
    
    /**
     * @var PermissionRepository
     */
    protected $permissionRepo;

  /**
     * RolesController constructor.
     *
     * @param RoleRepository $roles
     */
    public function __construct(BaseRepository $baseRepo,Role $role,RoleRepository $roleRepo,PermissionRepository $permissionRepo,Permission $permission)
    {
        $this->baseRepo = $baseRepo;
        $this->roleRepo = $roleRepo;
        $this->role = $role;
        $this->permission = $permission;
        $this->permissionRepo = $permissionRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $roles=$this->roleRepo->all($this->role);
        return view('auth::roles.index',compact('roles'));
    }

    // methods for trash
    public function trash(){
        $roles=$this->roleRepo->trash($this->role);
        return view('auth::roles.trash',compact('roles'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions=$this->roleRepo->all($this->permission);
        $statuses = $this->baseRepo->getStatuses();
        return view('auth::roles.create',compact('permissions','statuses'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $this->roleRepo->store($request,$this->role);
        return redirect()->route('admin.roles.create')->with('flash_message_success','created successfully');

        
    

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $role=$this->roleRepo->find($id,$this->role);
        return view('auth::roles.show',compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role=$this->roleRepo->find($id,$this->role);
        $permissionsRole=$this->permissionRepo->permissionsRole($role);
        $permissions=$this->permissionRepo->all($this->permission);
        $statuses = $this->baseRepo->getStatuses();
        return view('auth::roles.edit',compact('role','permissions','permissionsRole','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {   
        $this->roleRepo->update($request,$id,$this->role);
        return redirect()->route('admin.roles.edit',$id)->with('flash_message_success','updated successfully');
    }
    
    //methods for restoring
    public function restore($id){
        $this->roleRepo->restore($id,$this->role);
        return redirect()->route('admin.roles.trash')->with('flash_message_success','restored successfully');

    }
    public function restoreAll(){
        $this->roleRepo->restoreAll($this->role);
        return redirect()->route('admin.roles.trash')->with('flash_message_success','restored all successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRoleRequest $request,$id)
    {
        $this->roleRepo->destroy($id,$this->role);
        return redirect()->route('admin.roles.index')->with('flash_message_success','deleted successfully, you can see it in trash');

    }
    public function forceDelete(DeleteRoleRequest $request,$id)
    {
        $this->roleRepo->forceDelete($id,$this->role);
        return redirect()->route('admin.roles.trash')->with('flash_message_success','force deleted successfully');

    }
}
