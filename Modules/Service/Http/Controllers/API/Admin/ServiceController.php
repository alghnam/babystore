<?php

namespace Modules\Service\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Service\Entities\Service;
use Modules\Service\Http\Requests\StoreServiceRequest;
use Modules\Service\Http\Requests\UpdateServiceRequest;
// use Modules\Service\Http\Requests\UpdateServiceRequest;
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
    // $this->middleware(['permission:services_read'])->only(['index','getAllPaginates']);
    // $this->middleware(['permission:services_trash'])->only('trash');
    // $this->middleware(['permission:services_restore'])->only('restore');
    // $this->middleware(['permission:services_restore-all'])->only('restore-all');
    // $this->middleware(['permission:services_show'])->only('show');
    // $this->middleware(['permission:services_store'])->only('store');
    // $this->middleware(['permission:services_update'])->only('update');
    // $this->middleware(['permission:services_destroy'])->only('destroy');
    // $this->middleware(['permission:services_destroy-force'])->only('destroy-force');
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
    
    $services=$this->serviceRepo->all($this->service);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Services has been getten successfully',
        'data'=> $services
    ]);
    }

    public function getAllPaginates(Request $request){
    
    $services=$this->serviceRepo->getAllPaginates($this->service,$request);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Services has been getten successfully(pagination)',
        'data'=> $services
    ]);
    }




    // methods for trash
    public function trash(Request $request){
    $services=$this->serviceRepo->trash($this->service,$request);

    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Services has been getten successfully (in trash)',
        'data'=> $services
    ]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreServiceRequest $request)
    {
    $service=$this->serviceRepo->store($request,$this->service);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Service has been stored successfully',
        'data'=> $service
    ]);
    }
    

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
    $service=$this->serviceRepo->find($id,$this->service);
    
        if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }
   
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'Service has been getten successfully',
            'data'=> $service
        ]);
    
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
    $service= $this->serviceRepo->update($request,$id,$this->service);
    if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Service has been updated successfully',
        'data'=> $service
    ]);
    

    }

    public function inventory(){
    $servicesInInventory= $this->serviceRepo->servicesInInventory($this->service);
    if(empty($servicesInInventory)){
    if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'ServicesInInventory getting successfully',
        'data'=> $servicesInInventory
    ]);
     
    }
    }

    //methods for restoring
    public function restore($id){
    
    $service =  $this->serviceRepo->restore($id,$this->service);
     if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }
    
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'Service has been restored',
                'data'=> $service
            ]);
        

    }
    public function restoreAll(){
    $services =  $this->serviceRepo->restoreAll($this->service);
     if(is_string($services)){
            return response()->json(['status'=>false,'message'=>$services],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'restored successfully',
            'data'=> $services
        ]);
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteServiceRequest $request,$id)
    {
    $service= $this->serviceRepo->destroy($id,$this->service);
     if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }
  
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'destroyed  successfully',
        'data'=> $service
    ]); 
    
    }
    public function forceDelete(DeleteServiceRequest $request,$id)
    {
    //to make force destroy for a Service must be this Service  not found in Services table  , must be found in trash Services
    $service=$this->serviceRepo->forceDelete($id,$this->service);
     if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }

        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed forcely successfully ',
            'data'=> null
        ]); 
    
    }
    
    
   
}
