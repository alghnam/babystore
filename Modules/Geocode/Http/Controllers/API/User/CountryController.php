<?php

namespace Modules\Geocode\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Geocode\Entities\Country;
use Modules\Country\Http\Requests\AddToCountryRequest;
use Modules\Geocode\Repositories\Country\CountryRepository;

class CountryController extends Controller
{
          /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var CountryRepository
     */
    protected $countryRepo;
    /**
     * @var Country
     */
    protected $country;
   

    /**
     * CountriesController constructor.
     *
     * @param CountryRepository $countries
     */
    public function __construct(BaseRepository $baseRepo, Country $country,CountryRepository $countryRepo)
    {

        $this->baseRepo = $baseRepo;
        $this->country = $country;
        $this->countryRepo = $countryRepo;
    }

        public function getAllCountries(){
        
         try{
        $countries=$this->countryRepo->getAllCountries($this->country);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countries],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
     

 
}
