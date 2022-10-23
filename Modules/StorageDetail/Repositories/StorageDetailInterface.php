<?php
namespace Modules\StorageDetail\Repositories;

interface StorageDetailRepositoryInterface
{
   public function store($model,$request);
   public function update($request,$id,$model);

}
