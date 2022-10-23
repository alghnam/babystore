<?php
namespace Modules\Geocode\Repositories\Town;

interface TownRepositoryInterface
{
   public function townsCity($model,$town);
   public function countryTown($town);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);
}
