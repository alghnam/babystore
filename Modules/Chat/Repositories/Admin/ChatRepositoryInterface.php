<?php
namespace Modules\Chat\Repositories\Admin;

interface ChatRepositoryInterface
{
   public function trash($model,$request);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);

}
