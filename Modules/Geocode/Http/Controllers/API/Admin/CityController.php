<?php

namespace Modules\Geocode\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Geocode\Http\Requests\City\DeleteCityRequest;
use Modules\Geocode\Http\Requests\City\StoreCityRequest;
use Modules\Geocode\Http\Requests\City\UpdateCityRequest;
use Modules\Geocode\Repositories\City\CityRepository;
use  Modules\Geocode\Entities\City;
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
     * CountriesController constructor.
     *
     * @param CityRepository $cities
     */
    public function __construct(BaseRepository $baseRepo, City $city,CityRepository $cityRepo)
    {
        $this->middleware(['permission:countries_read'])->only(['index','getAllPaginates']);
        $this->middleware(['permission:countries_trash'])->only('trash');
        $this->middleware(['permission:countries_restore'])->only('restore');
        $this->middleware(['permission:countries_restore-all'])->only('restore-all');
        $this->middleware(['permission:countries_show'])->only('show');
        $this->middleware(['permission:countries_store'])->only('store');
        $this->middleware(['permission:countries_update'])->only('update');
        $this->middleware(['permission:countries_destroy'])->only('destroy');
        $this->middleware(['permission:countries_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->city = $city;
        $this->cityRepo = $cityRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
          try{
        $cities=$this->cityRepo->all($this->city);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cities],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllPaginates(Request $request){
        
         try{
        $cities=$this->cityRepo->getAllPaginates($this->city,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cities],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getCountriesProduct(Request $request,$productId){
        
         try{
        $cities=$this->cityRepo->getCountriesProduct($this->city,$request,$productId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cities],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
        $cities=$this->cityRepo->trash($this->city,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cities],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        //  try{
       $city= $this->cityRepo->store($request,$this->city);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$city->load('country')],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $city=$this->cityRepo->find($id,$this->city);
            if(is_string($city)){
                return response()->json(['status'=>false,'message'=>$city],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$city->load('country')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

 

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request,$id)
    {

          try{
            $city= $this->cityRepo->update($request,$id,$this->city);
             if(is_string($city)){
            return response()->json(['status'=>false,'message'=>$city],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$city->load('country')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
          try{
        $city =  $this->cityRepo->restore($id,$this->city);
                                  if(is_string($city)){
            return response()->json(['status'=>false,'message'=>$city],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$city->load('country')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
          try{
            $cities =  $this->cityRepo->restoreAll($this->city);
            if(is_string($cities)){
                return response()->json(['status'=>false,'message'=>$cities],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cities],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
        
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCityRequest $request,$id)
    {
          try{
            $city= $this->cityRepo->destroy($id,$this->city);
               if(is_string($city)){
                    return response()->json(['status'=>false,'message'=>$city],404);
                }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$city->load('country')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteCityRequest $request,$id)
    {
          try{
            //to make force destroy for a City must be this City  not found in Countries table  , must be found in trash Countries
            $city=$this->cityRepo->forceDelete($id,$this->city);
             if(is_string($city)){
            return response()->json(['status'=>false,'message'=>$city],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$city],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


 
}
