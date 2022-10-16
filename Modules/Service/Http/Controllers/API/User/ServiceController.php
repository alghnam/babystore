<?php

namespace Modules\Service\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Service\Repositories\User\ServiceRepository;
use Modules\Service\Entities\Service;

class ServiceController extends Controller
{
         /**
     * @var Service
     */
    protected $service;

         /**
     * @var ServiceRepository
     */
    protected $serviceRepo;
    /**
     * OrdersController constructor.
     *
     * @param OrderRepository $services
     */
    public function __construct(BaseRepository $baseRepo, Service $service, ServiceRepository $serviceRepo)
    {
             $this->middleware(['permission:services_get'])->only('getServices');
             $this->middleware(['permission:services_show'])->only('showService');
        $this->baseRepo = $baseRepo;
        $this->service = $service;
        $this->serviceRepo = $serviceRepo;
       

    }
    public function getServices(){
        $services=$this->serviceRepo->getServices($this->service);
            return response()->json(['status'=>true,'message'=>'تم ايجاد الخدمات بنجاح','data'=>$services],200);

    }
    public function showService($id){
        try{
        $service=$this->serviceRepo->showService($this->service,$id);
        
                if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$service],200);
    }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
}
