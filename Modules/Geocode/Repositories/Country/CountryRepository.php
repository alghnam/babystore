<?php
namespace Modules\Geocode\Repositories\Country;

use App\Repositories\EloquentRepository;
use Modules\Geocode\Entities\Country;
use Modules\Geocode\Repositories\Country\CountryRepositoryInterface;
use App\Scopes\ActiveScope;

class CountryRepository extends EloquentRepository implements CountryRepositoryInterface
{
        public function all($model){
    $modelData=$model->get();
       return  $modelData;
   }
              public function getAllPaginates($model,$request){
        $modelData=$model->with(['cities'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
     public function getAllCountries($model){
                //  $countries= $model->pluck('name','id')->toArray();

        $countries= $model->get();
        return $countries;
    }
         public function getAllCitiesCountry($model,$countryId){
           $country=  $model->where('id',$countryId)->first();
           $getAllCitiesCountry=$country->cities()->get();
            return $getAllCitiesCountry;
    }
             public function getAllTownsCity($model,$cityId){
           $city=  $model->where('id',$cityId)->first();
           $getAllTownsCity=$city->towns()->get();
            return $getAllTownsCity;
    }

//this methods for countryRepo only
    public function CountriesUser($user){
        $countriesUser= $user->Countries->pluck('id')->toArray();
        return $countriesUser;
    }

    
    // methods overrides
    public function store($request,$model){
        $data=$request->validated();        
        $country=  $model->create($data);

       return $country;
    
    }
    public function update($request,$id,$model){
        $country=$model->findOrFail($id);
        $country->update($request->validated());

        return $country;
    }




}
