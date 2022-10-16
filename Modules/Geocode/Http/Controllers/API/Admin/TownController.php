<?php

namespace Modules\Geocode\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Geocode\Http\Requests\Town\DeleteTownRequest;
use Modules\Geocode\Http\Requests\Town\StoreTownRequest;
use Modules\Geocode\Http\Requests\Town\UpdateTownRequest;
use Modules\Geocode\Repositories\Town\TownRepository;
use  Modules\Geocode\Entities\Town;
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
     * CountriesController constructor.
     *
     * @param TownRepository $cities
     */
    public function __construct(BaseRepository $baseRepo, Town $town,TownRepository $townRepo)
    {
        $this->middleware(['permission:towns_read'])->only(['index','getAllPaginates']);
        $this->middleware(['permission:towns_trash'])->only('trash');
        $this->middleware(['permission:towns_restore'])->only('restore');
        $this->middleware(['permission:towns_restore-all'])->only('restore-all');
        $this->middleware(['permission:towns_show'])->only('show');
        $this->middleware(['permission:towns_store'])->only('store');
        $this->middleware(['permission:towns_update'])->only('update');
        $this->middleware(['permission:towns_destroy'])->only('destroy');
        $this->middleware(['permission:towns_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->town = $town;
        $this->townRepo = $townRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
          try{
        $cities=$this->townRepo->all($this->town);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cities],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllPaginates(Request $request){
        
         try{
             $cities=$this->townRepo->getAllPaginates($this->town,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cities],200);

           
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getCountriesProduct(Request $request,$productId){
        
         try{
            $cities=$this->townRepo->getCountriesProduct($this->town,$request,$productId);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cities],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
        $cities=$this->townRepo->trash($this->town,$request);
          if(is_string($cities)){
                return response()->json(['status'=>false,'message'=>$cities],404);
            }
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
    public function store(StoreTownRequest $request)
    {
         try{
            $town= $this->townRepo->store($request,$this->town);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$town->load('city')],200);

        
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
        $town=$this->townRepo->find($id,$this->town);
                          if(is_string($town)){
            return response()->json(['status'=>false,'message'=>$town],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$town->load('city')],200);

        
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
    public function update(UpdateTownRequest $request,$id)
    {

          try{
       $town= $this->townRepo->update($request,$id,$this->town);
                                 if(is_string($town)){
            return response()->json(['status'=>false,'message'=>$town],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$town->load('city')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
          try{
        $town =  $this->townRepo->restore($id,$this->town);
                                  if(is_string($town)){
            return response()->json(['status'=>false,'message'=>$town],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$town->load('city')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
          try{
        $cities =  $this->townRepo->restoreAll($this->town);
                                  if(is_string($cities)){
            return response()->json(['status'=>false,'message'=>$cities],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'message'=>'تمت الاستعادة بنجاح','data'=>$cities],200);

        
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
    public function destroy(DeleteTownRequest $request,$id)
    {
        try{
            $town= $this->townRepo->destroy($id,$this->town);
            if(is_string($town)){
            return response()->json(['status'=>false,'message'=>$town],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$town->load('city')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteTownRequest $request,$id)
    {
          try{
        //to make force destroy for a Town must be this Town  not found in Countries table  , must be found in trash Countries
        $town=$this->townRepo->forceDelete($id,$this->town);
                          if(is_string($town)){
            return response()->json(['status'=>false,'message'=>$town],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$town],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




 
}
