<?php
namespace Modules\Auth\Repositories\Permission;

use App\Repositories\EloquentRepository;
use Modules\Auth\Entities\Permission;
use Modules\Auth\Repositories\Permission\PermissionRepositoryInterface;
use App\Scopes\ActiveScope;

class PermissionRepository extends EloquentRepository implements PermissionRepositoryInterface
{
    /**
     * @var EloquentRepository
     */
    protected $eloquentRepo;
        /**
     * @var Permission
     */
    protected $permission;
    public function __construct(EloquentRepository $eloquentRepo,Permission $permission)
    {
        $this->eloquentRepo = $eloquentRepo;
        $this->permission = $permission;
    }

    public function PermissionsUser($user){
        $permissionsUser= $user->permissions->pluck('id')->toArray();
        return $permissionsUser;
    }
    public function PermissionsRole($role){
        $permissionsRole= $role->permissions->pluck('id')->toArray();
        return $permissionsRole;
    }
        public function permissionsRoleByName($model,$roleId){
            $role=$model->find($roleId);
        $permissionsRole= $role->permissions()->get();
        return $permissionsRole;
    }


     public function storePermissionForModule($request,$model){
        $data=$request->validated();//name per , module name , display name , dec
        $countNamesPermissionsForModule=$model->where(['name'=>$data['name'],'module_name'=>$data['module_name']])->count();
        if($countNamesPermissionsForModule!==0){
            return false;
        }else{
             $model->create($data);
          //  if($request->roles){
            //    $item->roles()->attach($data['roles']);
            //}
            return true;
        }
    }
    public function storePermissionForRole($request,$model){
        //name per , module name , display name , dec
        $data=$request->validated();//roles,status

        $item= $model->create($data);
        if($request->roles){
            $item->roles()->attach($data['roles']);
        }
            return $item;
        
    }
    public function update($request,$id,$model){

        $item=$this->find($id,$model);
        $item->update($request->validated());
        if($request->roles){
            $item->roles()->sync($request->roles);
        }
        return $item;
    }

    // methods overrides

    public function forceDelete($id,$model){
          //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
          $itemInTableitems = $this->find($id,$model);//find this item from  table items
          if(empty($itemInTableitems)){//this item not found in items table
              $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
              if(empty($itemInTrash)){//this item not found in trash items
            //   return __('this item  found in system so you cannt   delete it by forcely , you can delete it Temporarily after that delete it by forcely');
            
            return 'هذا العنصر  غير موجود بسلة المحذوفات لذلك يمكنك حذفه من النظام بالبداية وبعد ذلك حذفه من سلة المحذوفات  ';
              }else{
                if($itemInTrash->roles){
                    $itemInTrash->roles()->detach($itemInTrash->roles);
                }
                  $itemInTrash->forceDelete();
                  return $itemInTrash;
              }
          }else{

            // return __('not found');
            return 'غير موجود بالنظام ';
                  
                            }
  
    }
}
