<?php
namespace Modules\Geocode\Repositories\Town;

use App\Repositories\EloquentRepository;
use Modules\Geocode\Entities\City;
use Modules\Geocode\Entities\Town;
use Modules\Geocode\Repositories\Town\TownRepositoryInterface;
use App\Scopes\ActiveScope;

class TownRepository extends EloquentRepository implements TownRepositoryInterface
{
                  public function getAllPaginates($model,$request){
        $modelData=$model->with(['city'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
//this methods for townRepo only
    public function cityTown($town){
        $cityTown= $town->city;
        return $cityTown;
    }
    public function countryTown($town){
        $countryTown= $town->country;
        return $countryTown;
    }
    public function townsCity($model,$cityId){
        $city=$model->where(['id'=>$cityId])->first();
        if(empty($city)){
            return 'غير موجود بالنظام';
        }
        $townsCity= $city->towns->all();
        return $townsCity;
    }
    
    // methods overrides
    public function store($request,$model){
        $data=$request->validated();        
        $town=  $model->create($data);
        if($request->roles){
            $town->roles()->attach($data['roles']);
        }
       return $town;
    
    }
    public function update($request,$id,$model){
        $town=$model->findOrFail($id);
        $town->update($request->validated());
        if($request->roles){
            $town->roles()->sync($request->roles);
        }
        return $town;
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
