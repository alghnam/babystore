<?php
namespace Modules\Auth\Repositories\Role;

interface RoleRepositoryInterface
{
   public function rolesUserByNameModel($model,$user);
   public function rolesUserByName($user);
   public function rolesUser($user);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);

}
