<?php

namespace Modules\UpSell\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\UpSell\Entities\UpSell;
use Modules\UpSell\Http\Requests\StoreUpSellRequest;
use Modules\UpSell\Http\Requests\UpdateUpSellRequest;
use Modules\UpSell\Http\Requests\DeleteUpSellRequest;
use Modules\UpSell\Http\Requests\UpdateDetailsUpSellRequest;
use Modules\UpSell\Repositories\User\UpSellRepository;
class UpSellController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var UpSellRepository
    */
    protected $upsellRepo;
    /**
    * @var UpSell
    */
    protected $upsell;


    /**
    * UpSellsController constructor.
    *
    * @param UpSellRepository $upsells
    */
    public function __construct(BaseRepository $baseRepo, UpSell $upsell,UpSellRepository $upsellRepo)
    {
        $this->middleware(['permission:up_sells_get'])->only('upsellsProduct','productAttrs');

        $this->baseRepo = $baseRepo;
        $this->upsell = $upsell;
        $this->upsellRepo = $upsellRepo;
    }




    public function upsellsProduct($productId){
        try{
           $upsellsProduct=$this->upsellRepo->getUpsellsProduct($productId);
              if(is_string($upsellsProduct)){
            return response()->json(['status'=>false,'message'=>$upsellsProduct],404);
        }
        return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsellsProduct],200);
    }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
        public function productAttrs($productId){
            try{
           $productAttrs=$this->upsellRepo->productAttrs($productId);
              if(is_string($productAttrs)){
            return response()->json(['status'=>false,'message'=>$productAttrs],404);
        }
        return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttrs],200);
    }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
  
    
   
}
