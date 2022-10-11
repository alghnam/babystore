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
        $country=Country::find($data['country_id']);
        $city->country()->associate($country);
        if($request->roles){
            $city->roles()->attach($data['roles']);
        }
       return $city;
    
    }
    public function update($request,$id,$model){

        $city=$model->findOrFail($id);
        $city->update($request->validated());
        $country=Country::find($request->country);
        $city->country()->associate($country);
        if($request->roles){
            $city->roles()->sync($request->roles);
        }
        return $city;
    }


    public function forceDelete($id,$model){
        //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
        $itemInTableitems = $this->find($id,$model);//find this item from  table items
        if(empty($itemInTableitems)){//this item not found in items table
            $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
            if(empty($itemInTrash)){//this item not found in trash items
                return 404;
            }else{
                $itemInTrash->country()->dissociate();

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
