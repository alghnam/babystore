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

    $this->baseRepo = $baseRepo;
    $this->upsell = $upsell;
    $this->upsellRepo = $upsellRepo;
    }




    public function upsellsProduct($productId){
           $upsellsProduct=$this->upsellRepo->getUpsellsProduct($productId);
              if(is_string($upsellsProduct)){
            return response()->json(['status'=>false,'message'=>$upsellsProduct],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSells product has been getten successfully',
        'data'=> $upsellsProduct
    ]); 
    }
    
        public function productAttrs($productId){
           $productAttrs=$this->upsellRepo->productAttrs($productId);
              if(is_string($productAttrs)){
            return response()->json(['status'=>false,'message'=>$productAttrs],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'productAttrs  has been getten successfully',
        'data'=> $productAttrs
    ]); 
    }
  
    
   
}
