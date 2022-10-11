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
    public function CountriesRole($role){
        $countriesRole= $role->Countries->pluck('id')->toArray();
        return $countriesRole;
    }
    
    // methods overrides
    public function store($request,$model){
        $data=$request->validated();        
        $country=  $model->create($data);
        if($request->roles){
            $country->roles()->attach($data['roles']);
        }
       return $country;
    
    }
    public function update($request,$id,$model){
        $country=$model->findOrFail($id);
        $country->update($request->validated());
        if($request->roles){
            $country->roles()->sync($request->roles);
        }
        return $country;
    }




    public function forceDelete($id,$model){
        //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
        $itemInTableitems = $this->find($id,$model);//find this item from  table items
        if(empty($itemInTableitems)){//this item not found in items table
            $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
            if(empty($itemInTrash)){//this item not found in trash items
                return 404;
            }else{
                if($itemInTrash->roles){
                    $itemInTrash->roles()->detach($itemInTrash->roles);
                }
                $itemInTrash->forceDelete();
                return 200;
            }
        }else{
            return 400;
        }


    }

}
