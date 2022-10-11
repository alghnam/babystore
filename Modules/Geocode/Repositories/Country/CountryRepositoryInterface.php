<?php
namespace Modules\Geocode\Repositories\Country;

interface CountryRepositoryInterface
{
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);
}
