<?php
namespace Modules\Category\Repositories\Admin;

interface CategoryRepositoryInterface
{
   public function getAllCategoriesPaginate($model,$request);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);

}
