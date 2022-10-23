<?php
namespace Modules\Geocode\Repositories\City;

use App\Repositories\EloquentRepository;
use Modules\Geocode\Entities\City;
use Modules\Geocode\Entities\Country;
use Modules\Geocode\Repositories\City\CityRepositoryInterface;
use App\Scopes\ActiveScope;

class CityRepository extends EloquentRepository implements CityRepositoryInterface
{
            public function all($model){
    $modelData=$model->get();
       return  $modelData;
   }
                  public function getAllPaginates($model,$request){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->with(['country','towns'])->paginate($request->total);
          return  $modelData;
    }
//this methods for cityRepo only

    public function countryCity($city){
        $countryCity= $city->country;
        return $countryCity;
    }
    
    public function citiesCountry($model,$countryId){
        $country=$model->where(['id'=>$countryId])->first();
        if(empty($country)){
            return 'غير موجود بالنظام';
        }
        $citiesCountry= $country->cities->all();
        return $citiesCountry;
    }


    // methods overrides

    public function store($request,$model){
        $data=$request->validated();        
        $city=  $model->create($data);

       return $city;
    
    }
    public function update($request,$id,$model){
        $data=$request->validated();
        $city=$model->findOrFail($id);
        $city->update($data);

        return $city;
    }



}
