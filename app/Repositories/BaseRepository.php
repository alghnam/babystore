<?php
namespace App\Repositories;

use App\Repositories\BaseRepositoryInterface;
use Modules\Auth\Entities\User;
use Modules\Auth\Repositories\User\UserRepository;
use Modules\Auth\Repositories\Role\RoleRepository;
use App\Providers\RouteServiceProvider;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Storage;

class BaseRepository extends EloquentRepository implements BaseRepositoryInterface
{
    /**
     * @var UserRepository
     */
    protected $userRepo;
    /**
     * @var RoleRepository
     */
    protected $roleRepo;
    /**
     * @var User
     */
    protected $user;
    /**
     * BaseRepository constructor.
     *
     */
    public function __construct(UserRepository $userRepo, RoleRepository $roleRepo, User $user)
    {
        $this->user = $user;
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;

    }
    public function redirectTo(){
        $user=User::find(auth()->user()->id);
        $rolesUser= $user->roles->pluck('name')->toArray();
        $existRoleSuperadministrator=  in_array('superadministrator',$rolesUser);
        if ( $existRoleSuperadministrator==true) {
            return route(RouteServiceProvider::DASHBOARD);
        }else{
            return route(RouteServiceProvider::HOME);
        }
    }

    public function authorize(){
        $user= $this->find(auth()->user()->id,$this->user);
        $rolesUser=$this->roleRepo->rolesUserByName($user);
        $existRoleSuperadministrator=  in_array('superadministrator',$rolesUser);
        if($existRoleSuperadministrator==true){
            return true;
        }else{
            return false;
        }
    }
    public function authorizeSuperAndAdmin(){
        $user= $this->find(auth()->user()->id,$this->user);
        $rolesUser=$this->roleRepo->rolesUserByName($user);
        $existRoleadministrator=  in_array('administrator',$rolesUser);
        $existSuperRoleadministrator=  in_array('superadministrator',$rolesUser);
        if($existSuperRoleadministrator==true||$existRoleadministrator==true){
            return true;
        }else{
            return false;
        }
    }

    public function getStatuses(){
        $statusCollection=collect([
            ['id'=>0,'status'=>'InActive'],
            ['id'=>1,'status'=>'Active']
           ]);
        return $statusCollection->pluck('id');
    }
    



    
}