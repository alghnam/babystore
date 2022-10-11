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
        // $this->middleware(['permission:services_read'])->only('index');
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
    public function getServices(){

        $services=$this->serviceRepo->getServices($this->service);
            return response()->json(['status'=>true,'message'=>'تم ايجاد الخدمات بنجاح','data'=>$services],200);

    }
    public function showService($id){

        $service=$this->serviceRepo->showService($this->service,$id);
        
                if(is_string($service)){
            return response()->json(['status'=>false,'message'=>$service],404);
        }
                    return response()->json(['status'=>true,'message'=>'تم ايجاد الخدمة بنجاح','data'=>$service],200);

    }
}
