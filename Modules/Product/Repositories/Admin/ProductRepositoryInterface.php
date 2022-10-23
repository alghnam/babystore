<?php
namespace Modules\Product\Repositories\Admin;

interface ProductRepositoryInterface
{
   public function getAllPaginates($model,$request);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);

}
