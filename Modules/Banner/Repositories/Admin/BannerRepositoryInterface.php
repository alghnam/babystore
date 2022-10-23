<?php
namespace Modules\Banner\Repositories\Admin;

interface BannerRepositoryInterface
{
   public function getAllPaginates($model,$request);
   public function trash($model,$request);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);

}
