<?php

namespace Modules\Geocode\Http\Controllers\WEB;
use App\Http\Controllers\Controller;
use Modules\Geocode\Http\Requests\City\StoreCityRequest;
use Modules\Geocode\Http\Requests\City\UpdateCityRequest;
use Modules\Geocode\Http\Requests\City\EditCityRequest;
use Modules\Geocode\Http\Requests\City\CreateCityRequest;
use Modules\Geocode\Http\Requests\City\DeleteCityRequest;
use Modules\Geocode\Repositories\Country\CountryRepository;
use Modules\Geocode\Repositories\City\CityRepository;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Modules\Geocode\Entities\City;
use Modules\Geocode\Entities\Country;

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
     * @var CountryRepository
     */
    protected $countryRepo;

     /**
     * @var City
     */
    protected $city;
    /**
     * @var Country
     */
    protected $country;


    /**
     * citiesController constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo, CityRepository $cityRepo,CountryRepository $countryRepo, City $city,Country $country)
    {
        $this->baseRepo = $baseRepo;
        $this->cityRepo = $cityRepo;
        $this->countryRepo = $countryRepo;
        $this->cityRepo = $cityRepo;
        $this->city = $city;
        $this->country = $country;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $cities=$this->cityRepo->all($this->city);
        return view('geocode::cities.index',compact('cities'));
    }

    // methods for trash
    public function trash(){
        $cities=$this->cityRepo->trash($this->city);
        return view('geocode::cities.trash',compact('cities'));
    }

    public function citiesCountry($countryId){
        $citiesCountry=$this->cityRepo->citiesCountry($countryId);
        return $citiesCountry;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCityRequest $request)
    {
        $countries=$this->countryRepo->all($this->country);
        $statuses = $this->baseRepo->getStatuses();
        return view('geocode::cities.create',compact('countries','statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $this->cityRepo->store($request,$this->city);

        return redirect()->route('admin.cities.create')->with('flash_message_success','created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $city=$this->cityRepo->find($id,$this->city);
        
        return view('geocode::cities.show',compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCityRequest $request,$id)
    {
        $countries=$this->countryRepo->all($this->country);

        $city=$this->cityRepo->find($id,$this->city);
        $countryCity=$this->cityRepo->countryCity($city);

        $statuses = $this->baseRepo->getStatuses();
        return view('geocode::cities.edit',compact('countries','city','statuses','countryCity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, $id)
    {

        $this->cityRepo->update($request,$id,$this->city);
       
        return redirect()->route('admin.cities.edit',$id)->with('flash_message_success','updated successfully');

    }

    //methods for restoring
    public function restore($id){
        $this->cityRepo->restore($id,$this->city);
        return redirect()->route('admin.cities.trash')->with('flash_message_success','restored successfully');

    }
    public function restoreAll(){
        $this->cityRepo->restoreAll($this->city);
        return redirect()->route('admin.cities.trash')->with('flash_message_success','restored all successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCityRequest $request,$id)
    {
        $this->cityRepo->destroy($id,$this->city);
        return redirect()->route('admin.cities.index')->with('flash_message_success','deleted successfully, you can see it in trash');

    }
    public function forceDelete(DeleteCityRequest $request,$id)
    {
        $this->cityRepo->forceDelete($id,$this->city);
        return redirect()->route('admin.cities.trash')->with('flash_message_success','force deleted successfully');

    }
}
