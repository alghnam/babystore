<?php
namespace Modules\Category\Repositories\Admin\SubCategory;

interface CategoryRepositoryInterface
{
   public function trash($model,$request);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);

}
