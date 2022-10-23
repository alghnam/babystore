<?php

namespace Modules\Service\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Service\Entities\Service;
use Modules\Service\Http\Requests\StoreServiceRequest;
use Modules\Service\Http\Requests\UpdateServiceRequest;
use Modules\Service\Http\Requests\DeleteServiceRequest;
use Modules\Service\Repositories\Admin\ServiceRepository;

class ServiceController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var ServiceRepository
    */
    protected $serviceRepo;
    /**
    * @var Service
    */
    protected $service;


    /**
    * ServicesController constructor.
    *
    * @param ServiceRepository $services
    */
    public function __construct(BaseRepository $baseRepo, Service $service,ServiceRepository $serviceRepo)
    {
    $this->middleware(['permission:services_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:services_trash'])->only('trash');
    $this->middleware(['permission:services_restore'])->only('restore');
    $this->middleware(['permission:services_restore-all'])->only('restore-all');
    $this->middleware(['permission:services_show'])->only('show');
    $this->middleware(['permission:services_store'])->only('store');
    $this->middleware(['permission:services_update'])->only('update');
    $this->middleware(['permission:services_destroy'])->only('destroy');
    $this->middleware(['permission:services_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->service = $service;
    $this->serviceRepo = $serviceRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
    
            $services=$this->serviceRepo->all($this->service);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$services],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllPaginates(Request $request){
        try{
            $services=$this->serviceRepo->getAllPaginates($this->service,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$services],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
            $services=$this->serviceRepo->trash($this->service,$request);
    
            if(is_string($services)){
                return response()->json(['status'=>false,'message'=>$services],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$services],200);

        
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
    public function store(StoreServiceRequest $request)
    {
        try{
    $service=$this->serviceRepo->store($request,$this->service);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$service],200);

        
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
    $service=$this->serviceRepo->find($id,$this->service);
    
        if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }
   
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$service],200);

        
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
    public function update(UpdateServiceRequest $request,$id)
    {
        try{
    $service= $this->serviceRepo->update($request,$id,$this->service);
    if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }
   
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$service],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

    }


    //methods for restoring
    public function restore($id){
        try{
            $service =  $this->serviceRepo->restore($id,$this->service);
             if(is_string($service)){
                    return response()->json(['status'=>false,'message'=>$service],404);
                }
    
   
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$service],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
        try{
            $services =  $this->serviceRepo->restoreAll($this->service);
             if(is_string($services)){
                    return response()->json(['status'=>false,'message'=>$services],404);
                }
           
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$services],200);

        
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
    public function destroy(DeleteServiceRequest $request,$id)
    {
        try{
            $service= $this->serviceRepo->destroy($id,$this->service);
             if(is_string($service)){
                    return response()->json(['status'=>false,'message'=>$service],404);
                }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$service],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function forceDelete(DeleteServiceRequest $request,$id)
    {
        try{
            //to make force destroy for a Service must be this Service  not found in Services table  , must be found in trash Services
            $service=$this->serviceRepo->forceDelete($id,$this->service);
             if(is_string($service)){
                    return response()->json(['status'=>false,'message'=>$service],404);
                }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$service],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
    
   
}
