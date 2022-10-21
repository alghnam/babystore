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

       return $town;
    
    }
    public function update($request,$id,$model){
        $town=$model->findOrFail($id);
        $town->update($request->validated());

        return $town;
    }




}
