<?php

namespace Modules\Geocode\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Geocode\Http\Requests\Town\DeleteTownRequest;
use Modules\Geocode\Http\Requests\Town\StoreTownRequest;
use Modules\Geocode\Http\Requests\Town\UpdateTownRequest;
use Modules\Geocode\Repositories\Town\TownRepository;
use  Modules\Geocode\Entities\Town;
use  Modules\Geocode\Entities\City;
use Modules\Town\Http\Requests\AddToTownRequest;

class TownController extends Controller
{
          /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var TownRepository
     */
    protected $townRepo;
    /**
     * @var Town
     */
    protected $town;
   
       /**
     * @var City
     */
    protected $city;

    /**
     * CountriesController constructor.
     *
     * @param TownRepository $cities
     */
    public function __construct(BaseRepository $baseRepo, Town $town,TownRepository $townRepo,City $city)
    {
        $this->baseRepo = $baseRepo;
        $this->town = $town;
        $this->city = $city;
        $this->townRepo = $townRepo;
    }
    
        public function getAllTownsCity($id){
        
         try{
            $towns=$this->townRepo->townsCity($this->city,$id);
            if(is_string($towns)){
                return response()->json(['status'=>false,'message'=>$towns],404);
            }
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$towns],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
       


 
}
