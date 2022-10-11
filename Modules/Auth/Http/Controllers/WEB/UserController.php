<?php

namespace Modules\Auth\Http\Controllers\WEB;
use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\User\StoreUserRequest;
use Modules\Auth\Http\Requests\User\UpdateUserRequest;
use Modules\Auth\Http\Requests\User\EditUserRequest;
use Modules\Auth\Http\Requests\User\CreateUserRequest;
use Modules\Auth\Http\Requests\User\DeleteUserRequest;
use Modules\Auth\Repositories\User\UserRepository;
use Modules\Auth\Repositories\Role\RoleRepository;
use Modules\Auth\Repositories\Permission\PermissionRepository;
use Modules\Geocode\Repositories\Country\CountryRepository;
use Modules\Geocode\Repositories\City\CityRepository;
use Modules\Geocode\Repositories\Town\TownRepository;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Modules\Auth\Entities\Permission;
use Modules\Auth\Entities\Role;
use Modules\Auth\Entities\User;
use Modules\Geocode\Entities\City;
use Modules\Geocode\Entities\Country;
use Modules\Geocode\Entities\Town;

class UserController extends Controller
{
    /**
     * @var BaseRepository
     */
    protected $baseRepo;
    
    /**
     * @var User
     */
    protected $user;
        /**
     * @var Role
     */
    protected $role;
        /**
     * @var Permission
     */
    protected $permission;
        /**
     * @var Country
     */
    protected $country;
        /**
     * @var City
     */
    protected $city;
        /**
     * @var Town
     */
    protected $town;
    /**
     * @var UserRepository
     */
    protected $userRepo;
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
    public function __construct(BaseRepository $baseRepo,User $user,Role $role,Country $country,City $city,Town $town,Permission $permission, UserRepository $userRepo,RoleRepository $roleRepo,PermissionRepository $permissionRepo, CountryRepository $countryRepo,CityRepository $cityRepo,TownRepository $townRepo)
    {

        $this->baseRepo = $baseRepo;
        $this->userRepo = $userRepo;
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
        $this->country = $country;
        $this->city = $city;
        $this->town = $town;
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
        $this->countryRepo = $countryRepo;
        $this->cityRepo = $cityRepo;
        $this->townRepo = $townRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $users=$this->userRepo->all($this->user);
        return view('auth::users.index',compact('users'));
    }

    // methods for trash
    public function trash(){
        $users=$this->userRepo->trash($this->user);
        return view('auth::users.trash',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateUserRequest $request)
    {
        $roles=$this->roleRepo->all($this->role);
        $permissions=$this->permissionRepo->all($this->permission);
        $countries=$this->userRepo->all($this->country);
        $cities=$this->userRepo->all($this->city);
        $towns=$this->userRepo->all($this->town);
        $statuses = $this->baseRepo->getStatuses();
        return view('auth::users.create',compact('roles','permissions','statuses','countries','cities','towns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        // dd($request);
        $this->userRepo->store($request,$this->user);

        return redirect()->route('admin.users.create')->with('flash_message_success','created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=$this->userRepo->find($id,$this->user);
        
        return view('auth::users.show',compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditUserRequest $request,$id)
    {
        $user=$this->userRepo->find($id,$this->user);
        // $password=Crypt::decryptString($user->password);
        $rolesUser=$this->roleRepo->rolesUser($user);
        $permissionsUser=$this->permissionRepo->permissionsUser($user);
        $statuses = $this->baseRepo->getStatuses();
        $roles=$this->userRepo->all($this->role);
        $permissions=$this->userRepo->all($this->permission);
        
        $countries=$this->userRepo->all($this->country);
        $cities=$this->userRepo->all($this->city);
        $towns=$this->userRepo->all($this->town);
        $countryUser=$this->userRepo->countryUser($user);
        $cityUser=$this->userRepo->cityUser($user);
        $townUser=$this->userRepo->townUser($user);
        return view('auth::users.edit',compact('roles','permissions','user','permissionsUser','rolesUser','statuses','countries','cities','towns','countryUser','cityUser','townUser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $this->userRepo->update($request,$id,$this->user);
       
        return redirect()->route('admin.users.edit',$id)->with('flash_message_success','updated successfully');

    }

    //methods for restoring
    public function restore($id){
        $this->userRepo->restore($id,$this->user);
        return redirect()->route('admin.users.trash')->with('flash_message_success','restored successfully');

    }
    public function restoreAll(){
        $this->userRepo->restoreAll($this->user);
        return redirect()->route('admin.users.trash')->with('flash_message_success','restored all successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteUserRequest $request,$id)
    {
        $this->userRepo->destroy($id,$this->user);
        return redirect()->route('admin.users.index')->with('flash_message_success','deleted successfully, you can see it in trash');

    }
    public function forceDelete(DeleteUserRequest $request,$id)
    {
        $this->userRepo->forceDelete($id,$this->user);
        return redirect()->route('admin.users.trash')->with('flash_message_success','force deleted successfully');

    }
}
