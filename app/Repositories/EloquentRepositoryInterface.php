<?php
namespace App\Repositories;

interface BaseRepositoryInterface
{
   public function all($model);
   public function trash($model,$request);
   public function find($id,$model);
   public function findItemOnlyTrashed($id,$model);
   public function findAllItemsOnlyTrashed($model);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function restore($id,$model);
   public function restoreAll($model);
   public function destroy($id,$model);
   public function forceDelete($id,$model);

}
