<?php

namespace Modules\Geocode\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Geocode\Http\Requests\Country\DeleteCountryRequest;
use Modules\Geocode\Http\Requests\Country\StoreCountryRequest;
use Modules\Geocode\Http\Requests\Country\UpdateCountryRequest;
use Modules\Geocode\Repositories\Country\CountryRepository;
use Modules\Geocode\Entities\Country;
use Modules\Country\Http\Requests\AddToCountryRequest;

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
        $this->country = $country;
        $this->countryRepo = $countryRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
          try{
        $countries=$this->countryRepo->all($this->country);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countries],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllPaginates(Request $request){
        
        //  try{
        $countries=$this->countryRepo->getAllPaginates($this->country,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countries],200);

        //         }catch(\Exception $ex){
        //     return response()->json([
        //         'status'=>500,
        //         'message'=>'There is something wrong, please try again'
        //     ]);  
        // } 
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }
        public function getCountriesProduct(Request $request,$productId){
        
         try{
        $countries=$this->countryRepo->getCountriesProduct($this->country,$request,$productId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countries],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
        $countries=$this->countryRepo->trash($this->country,$request);
             if(is_string($countries)){
                return response()->json(['status'=>false,'message'=>$countries],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countries],200);

        
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
    public function store(StoreCountryRequest $request)
    {
         try{
       $country= $this->countryRepo->store($request,$this->country);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$country],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
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
        $country=$this->countryRepo->find($id,$this->country);
                          if(is_string($country)){
            return response()->json(['status'=>false,'message'=>$country],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$country],200);

        
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
    public function update(UpdateCountryRequest $request,$id)
    {

          try{
       $country= $this->countryRepo->update($request,$id,$this->country);
                                 if(is_string($country)){
            return response()->json(['status'=>false,'message'=>$country],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$country],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
          try{
        $country =  $this->countryRepo->restore($id,$this->country);
                                  if(is_string($country)){
            return response()->json(['status'=>false,'message'=>$country],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$country],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
          try{
        $countries =  $this->countryRepo->restoreAll($this->country);
                                  if(is_string($countries)){
            return response()->json(['status'=>false,'message'=>$countries],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countries],200);

        
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
    public function destroy(DeleteCountryRequest $request,$id)
    {
          try{
       $country= $this->countryRepo->destroy($id,$this->country);
                          if(is_string($country)){
            return response()->json(['status'=>false,'message'=>$country],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$country],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteCountryRequest $request,$id)
    {
          try{
        //to make force destroy for a Country must be this Country  not found in Countries table  , must be found in trash Countries
        $country=$this->countryRepo->forceDelete($id,$this->country);
                          if(is_string($country)){
            return response()->json(['status'=>false,'message'=>$country],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$country],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }



 
}
