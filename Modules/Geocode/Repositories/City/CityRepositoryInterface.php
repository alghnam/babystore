<?php
namespace Modules\Geocode\Repositories\City;

interface CityRepositoryInterface
{
   public function citiesCountry($model,$countryId);
   public function countryCity($city);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);
}
