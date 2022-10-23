<?php

namespace Modules\Auth\Http\Controllers\WEB;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\Permission\DeletePermissionRequest;
use Modules\Auth\Repositories\Permission\PermissionRepository;
use Modules\Auth\Repositories\Role\RoleRepository;
use Modules\Auth\Entities\Role;
use Modules\Auth\Entities\Permission;
use Modules\Auth\Http\Requests\Permission\StorePermissionRequest;
use Modules\Auth\Http\Requests\Permission\UpdatePermissionRequest;

class PermissionController extends Controller
{
    /**
     * @var BaseRepository
     */
    protected $baseRepo;
    
    /**
     * @var PermissionRepository
     */
    protected $permissionRepo;
    /**
     * @var RoleRepository
     */
    protected $roleRepo;

  /**
     * RolesController constructor.
     *
     * @param RoleRepository $roles
     */
    public function __construct(BaseRepository $baseRepo,PermissionRepository $permissionRepo,RoleRepository $roleRepo,Role $role,Permission $permission)
    {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
        $this->role = $role;
        $this->permission = $permission;
        $this->baseRepo = $baseRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $permissions=$this->permissionRepo->all($this->permission);
       return view('auth::permissions.index',compact('permissions'));
    }
    // methods for trash
    public function trash(){
        $permissions=$this->permissionRepo->trash($this->permission);
        return view('auth::permissions.trash',compact('permissions'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles=$this->permissionRepo->all($this->role);
        $statuses = $this->baseRepo->getStatuses();
        return view('auth::permissions.create',compact('roles','statuses'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionRequest $request)
    {
        $this->permissionRepo->store($request,$this->permission);
       return redirect()->back()->with('flash_message_success','created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission=$this->permissionRepo->find($id,$this->permission);
        return view('auth::permissions.show',compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission=$this->permissionRepo->find($id,$this->permission);
        $rolesPermission=$this->roleRepo->rolesPermission($permission);
        $roles=$this->permissionRepo->all($this->role);
        $statuses = $this->baseRepo->getStatuses();
        return view('auth::permissions.edit',compact('permission','roles','rolesPermission','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        $this->permissionRepo->update($request,$id,$this->permission);
        return redirect()->back()->with('flash_message_success','updated successfully');
       

    }

    //methods for restoring
    public function restore($id){
        $this->permissionRepo->restore($id,$this->permission);
        return redirect()->route('admin.permissions.trash')->with('flash_message_success','restored successfully');

    }
    public function restoreAll(){
        $this->permissionRepo->restoreAll($this->permission);
        return redirect()->route('admin.permissions.trash')->with('flash_message_success','restored all successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeletePermissionRequest $request,$id)
    {
        $this->permissionRepo->destroy($id,$this->permission);
        return redirect()->route('admin.permissions.index')->with('flash_message_success','deleted successfully, you can see it in trash');

    }
    public function forceDelete(DeletePermissionRequest $request,$id)
    {
        $this->permissionRepo->forceDelete($id,$this->permission);
        return redirect()->route('admin.permissions.trash')->with('flash_message_success','force deleted successfully');

    }
}
