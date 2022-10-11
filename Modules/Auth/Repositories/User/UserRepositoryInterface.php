<?php
namespace Modules\Auth\Repositories\User;

interface UserRepositoryInterface
{
   public function countryUser($user);
   public function cityUser($user);
   public function townUser($user);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);

}
