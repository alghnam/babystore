<?php

namespace Modules\Geocode\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Geocode\Http\Requests\City\DeleteCityRequest;
use Modules\Geocode\Http\Requests\City\StoreCityRequest;
use Modules\Geocode\Http\Requests\City\UpdateCityRequest;
use Modules\Geocode\Repositories\City\CityRepository;
use  Modules\Geocode\Entities\City;
use  Modules\Geocode\Entities\Country;
use Modules\City\Http\Requests\AddToCityRequest;

class CityController extends Controller
{
          /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var CityRepository
     */
    protected $cityRepo;
    /**
     * @var City
     */
    protected $city;
       /**
     * @var Country
     */
    protected $country;
   

    /**
     * CountriesController constructor.
     *
     * @param CityRepository $cities
     */
    public function __construct(BaseRepository $baseRepo, City $city,CityRepository $cityRepo,Country $country)
    {

        $this->baseRepo = $baseRepo;
        $this->country = $country;
        $this->city = $city;
        $this->cityRepo = $cityRepo;
    }

        public function getAllCitiesCountry($id){
        
         try{
        $cities=$this->cityRepo->citiesCountry($this->country,$id);
           if(is_string($cities)){
            return response()->json(['status'=>false,'message'=>$cities],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cities],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
       



 
}
