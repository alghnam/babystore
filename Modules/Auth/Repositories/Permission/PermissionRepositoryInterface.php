<?php
namespace Modules\Auth\Repositories\Permission;

interface PermissionRepositoryInterface
{
   public function PermissionsRole($role);
   public function PermissionsUser($role);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);
}
