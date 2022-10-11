<?php
namespace Modules\Auth\Repositories\Role;

use App\Repositories\EloquentRepository;
use Modules\Auth\Entities\Role;
use Modules\Auth\Repositories\Role\RoleRepositoryInterface;
use App\Scopes\ActiveScope;

class RoleRepository extends EloquentRepository implements RoleRepositoryInterface
{

    public function rolesUserByName($user){
        $rolesUser= $user->roles->pluck('name')->toArray();
        return  $rolesUser;
    }    
    public function rolesUserByNameModel($model,$userId){
       $user= $model->find($userId);//model->user
        $rolesUser= $user->roles()->get();
        // dd($rolesUser);
        // foreach($rolesUser as $r=>$k){
        // dd($k);
            
        // }
        return  $rolesUser;
    }
    public function rolesUser($user){
        $rolesUser= $user->roles->pluck('id')->toArray();
        return $rolesUser;
    }
    public function rolesPermission($permission){
        $rolesPermission= $permission->roles->pluck('id')->toArray();
        return $rolesPermission;
    }
        public function rolesPermissionByName($model,$permissionId){
           $permission= $model->find($permissionId);
        $rolesPermission= $permission->roles()->get();
        return $rolesPermission;
    }
    
    // methods overrides
    public function store($request,$model){
        $data=$request->validated();
       $role= $model->create($data);
       if(!empty($data['permissions'])){
       $role->permissions()->attach($data['permissions']);
       }
       return $role;
    
    }
    public function update($request,$id,$model){
        $role=$model->findOrFail($id);
        if(!empty($role)){

        $data=$request->validated();
        $role->update($data);
        if(!empty($data['permissions'])){
            $role->permissions()->sync($data['permissions']);
        }
    }
        return $role;
    }
    public function forceDelete($id,$model){
        //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
        $itemInTableitems = $this->find($id,$model);//find this item from  table items
        if(empty($itemInTableitems)){//this item not found in items table
            $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
            if(empty($itemInTrash)){//this item not found in trash items
            //   return __('this item  found in system so you cannt   delete it by forcely , you can delete it Temporarily after that delete it by forcely');
            
            return 'هذا العنصر  غير موجود بسلة المحذوفات لذلك يمكنك حذفه من النظام بالبداية وبعد ذلك حذفه من سلة المحذوفات  ';

 
            }else{
                $itemInTrash->detachPermissions($itemInTrash->permissions);
                $itemInTrash->forceDelete();
                return $itemInTrash;
            }
        }else{
            // return __('not found');
            return 'غير موجود بالنظام ';
                  
                  }



    }
}