<?php

namespace Modules\Geocode\Http\Controllers\WEB;
use App\Http\Controllers\Controller;
use Modules\Geocode\Http\Requests\Town\StoreTownRequest;
use Modules\Geocode\Http\Requests\Town\UpdateTownRequest;
use Modules\Geocode\Http\Requests\Town\EditTownRequest;
use Modules\Geocode\Http\Requests\Town\CreateTownRequest;
use Modules\Geocode\Http\Requests\Town\DeleteTownRequest;
use Modules\Geocode\Repositories\Town\TownRepository;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Modules\Geocode\Entities\City;
use Modules\Geocode\Entities\Country;
use Modules\Geocode\Entities\Town;
use Modules\Geocode\Repositories\City\CityRepository;
use Modules\Geocode\Repositories\Country\CountryRepository;

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
     * @var City
     */
    protected $city;
        /**
     * @var Country
     */
    protected $country;
        /**
     * @var Town
     */
    protected $town;


    
    /**
     * townsController constructor.
     *
     * @param TownRepository $towns
     */
    public function __construct(BaseRepository $baseRepo, TownRepository $townRepo , CountryRepository $countryRepo,CityRepository $cityRepo,Town $town , Country $country,City $city)
    {
        $this->baseRepo = $baseRepo;
        $this->townRepo = $townRepo;
        $this->cityRepo = $cityRepo;
        $this->countryRepo = $countryRepo;
        $this->town = $town;
        $this->city = $city;
        $this->country = $country;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $towns=$this->townRepo->all($this->town);
        return view('geocode::towns.index',compact('towns'));
    }

    // methods for trash
    public function trash(){
        $towns=$this->townRepo->trash($this->town);
        return view('geocode::towns.trash',compact('towns'));
    }

    public function townsCity($cityId){
        $townsCity=$this->townRepo->townsCity($cityId);
        return $townsCity;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateTownRequest $request)
    {
        $countries=$this->townRepo->all($this->country);
        $cities=$this->townRepo->all($this->city);
        $statuses = $this->baseRepo->getStatuses();
        return view('geocode::towns.create',compact('countries','cities','statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTownRequest $request)
    {
        $this->townRepo->store($request,$this->town);

        return redirect()->route('admin.towns.create')->with('flash_message_success','created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $town=$this->townRepo->find($id,$this->town);
        
        return view('geocode::towns.show',compact('towns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditTownRequest $request,$id)
    {
        $countries=$this->townRepo->all($this->country);
        $cities=$this->townRepo->all($this->city);
        $town=$this->townRepo->find($id,$this->town);
        $cityTown=$this->townRepo->cityTown($town);
        $countryCity=$this->cityRepo->countryCity($cityTown);

        $statuses = $this->baseRepo->getStatuses();
        return view('geocode::towns.edit',compact('countries','cities','town','cityTown','countryCity','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTownRequest $request, $id)
    {
        $this->townRepo->update($request,$id,$this->town);
       
        return redirect()->route('admin.towns.edit',$id)->with('flash_message_success','updated successfully');

    }

    //methods for restoring
    public function restore($id){
        $this->townRepo->restore($id,$this->town);
        return redirect()->route('admin.towns.trash')->with('flash_message_success','restored successfully');

    }
    public function restoreAll(){
        $this->townRepo->restoreAll($this->town);
        return redirect()->route('admin.towns.trash')->with('flash_message_success','restored all successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteTownRequest $request,$id)
    {
        $this->townRepo->destroy($id,$this->town);
        return redirect()->route('admin.towns.index')->with('flash_message_success','deleted successfully, you can see it in trash');

    }
    public function forceDelete(DeleteTownRequest $request,$id)
    {
        $this->townRepo->forceDelete($id,$this->town);
        return redirect()->route('admin.towns.trash')->with('flash_message_success','force deleted successfully');

    }
}
