<?php

namespace Modules\Geocode\Http\Controllers\WEB;
use App\Http\Controllers\Controller;
use Modules\Geocode\Http\Requests\Country\StoreCountryRequest;
use Modules\Geocode\Http\Requests\Country\UpdateCountryRequest;
use Modules\Geocode\Http\Requests\Country\EditCountryRequest;
use Modules\Geocode\Http\Requests\Country\CreateCountryRequest;
use Modules\Geocode\Http\Requests\Country\DeleteCountryRequest;
use Modules\Geocode\Repositories\Country\CountryRepository;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Modules\Geocode\Entities\Country;

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
     * countriesController constructor.
     *
     * @param countryRepository $countries
     */
    public function __construct(BaseRepository $baseRepo, CountryRepository $countryRepo, Country $country)
    {
        $this->baseRepo = $baseRepo;
        $this->countryRepo = $countryRepo;
        $this->country = $country;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $countries=$this->countryRepo->all($this->country);
        return view('geocode::countries.index',compact('countries'));
    }

    // methods for trash
    public function trash(){
        $countries=$this->countryRepo->trash($this->country);
        return view('geocode::countries.trash',compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCountryRequest $request)
    {
        $statuses = $this->baseRepo->getStatuses();
        return view('geocode::countries.create',compact('roles','permissions','statuses','countries','cities','towns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {
        $this->countryRepo->store($request,$this->country);

        return redirect()->route('admin.countries.create')->with('flash_message_success','created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country=$this->countryRepo->find($id,$this->country);
        
        return view('geocode::countries.show',compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCountryRequest $request,$id)
    {
        $statuses = $this->baseRepo->getStatuses();
        $country = $this->countryRepo->find($id,$this->country);
        return view('geocode::countries.edit',compact('statuses','country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        $this->countryRepo->update($request,$id,$this->country);
       
        return redirect()->route('admin.countries.edit',$id)->with('flash_message_success','updated successfully');

    }

    //methods for restoring
    public function restore($id){
        $this->countryRepo->restore($id,$this->country);
        return redirect()->route('admin.countries.trash')->with('flash_message_success','restored successfully');

    }
    public function restoreAll(){
        $this->countryRepo->restoreAll($this->country);
        return redirect()->route('admin.countries.trash')->with('flash_message_success','restored all successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCountryRequest $request,$id)
    {
        $this->countryRepo->destroy($id,$this->country);
        return redirect()->route('admin.countries.index')->with('flash_message_success','deleted successfully, you can see it in trash');

    }
    public function forceDelete(DeleteCountryRequest $request,$id)
    {
        $this->countryRepo->forceDelete($id,$this->country);
        return redirect()->route('admin.countries.trash')->with('flash_message_success','force deleted successfully');

    }
}
