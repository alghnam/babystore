<?php

namespace Modules\Order\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Http\Requests\DeleteOrderRequest;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Http\Requests\UpdateOrderRequest;

use Modules\Order\Repositories\Admin\OrderRepository;
use Modules\Order\Http\Requests\AddAddressRequest;
use Modules\Order\Http\Requests\UpdateAddressRequest;
use Modules\Order\Http\Requests\AddReviewOrderRequest;
use Modules\Order\Entities\Address;
use Modules\Order\Http\Requests\FinishingOrderRequest;
use Modules\Order\Entities\AddressCodeNum;
use Modules\Auth\Entities\User;
use Modules\Product\Entities\Product;
class OrderController extends Controller
{
    
           /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var OrderRepository
     */
    protected $orderRepo;

        /**
     * @var Order
     */
    protected $order;



   
           /**
     * @var Address
     */
    protected $address;


    /**
     * OrdersController constructor.
     *
     * @param OrderRepository $orders
     */
    public function __construct(BaseRepository $baseRepo, Order $order, Address $address,OrderRepository $orderRepo,AddressCodeNum $addressCodeNum)
    {
        $this->middleware(['permission:orders_read'])->only(['index','getAllPaginates','countData','finishedOrders']);
        $this->middleware(['permission:orders_trash'])->only('trash');
        $this->middleware(['permission:orders_restore'])->only('restore');
        $this->middleware(['permission:orders_restore-all'])->only('restore-all');
        $this->middleware(['permission:orders_show'])->only('show');
        $this->middleware(['permission:orders_store'])->only('store');
        $this->middleware(['permission:orders_update'])->only('update');
        $this->middleware(['permission:orders_destroy'])->only('destroy');
        $this->middleware(['permission:orders_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->order = $order;
        $this->addressCodeNum = $addressCodeNum;
        $this->address = $address;
        $this->orderRepo = $orderRepo;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        try{
            $orders=$this->orderRepo->all($this->order);
            
    
            if(is_string($orders)){
                return response()->json(['status'=>false,'message'=>$orders],400);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$orders],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
     public function countData(){
        $countData=$this->orderRepo->countData($this->order);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countData],200);
          
     }
    public function countsAllData(){
      $countsAllData=$this->orderRepo->countsAllData($this->order);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countsAllData],200);
          
   }
    //  public function countData(){
    //     $countData=$this->orderRepo->countData($this->order);
    //       return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countData],200);
          
    //  }
         public function pricesSentDeliveredOrders(){
         $pricesSentDeliveredOrders=$this->orderRepo->pricesSentDeliveredOrders($this->order);
         
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pricesSentDeliveredOrders],200);
          
     }

     public function sentDeliveredOrders(){
         $sentDeliveredOrders=$this->orderRepo->sentDeliveredOrders($this->order);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$sentDeliveredOrders],200);
          
     }
          public function shippingOrders(){
         $shippingOrders=$this->orderRepo->shippingOrders($this->order);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$shippingOrders],200);
          
     }
     public function showCurrency(){
        $showCurrency=$this->baseRepo->countryCurrency();
        return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$showCurrency],200);
     }
         public function getOrdersGroupMonth(){

            $getOrdersGroupMonth=$this->orderRepo->getOrdersGroupMonth($this->order);
           
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$getOrdersGroupMonth],200);
          
        }
        public function getAllPaginates(Request $request){
            // try{
                
                $orders=$this->orderRepo->getAllPaginates($this->order,$request);
             if(is_string($orders)){
                    return response()->json(['status'=>false,'message'=>$orders],400);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$orders],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }

   public function getLatestOrders(){
       try{
           $getLatestOrders=$this->orderRepo->getLatestOrders($this->order);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$getLatestOrders],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
   }




    // methods for trash
    public function trash(Request $request){
        try{
            $orders=$this->orderRepo->trash($this->order,$request);
            if(is_string($orders)){
                return response()->json(['status'=>false,'message'=>$orders],400);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$orders],200);

        
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
    public function store(StoreOrderRequest $request)
    {
        try{
            $order=$this->orderRepo->store($request,$this->order);
            if(is_string($order)){
                return response()->json(['status'=>false,'message'=>$order],400);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
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
        $order=$this->orderRepo->find($id,$this->order);
        if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
   
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
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
    public function update(UpdateOrderRequest $request,$id)
    {
        try{
           $order= $this->orderRepo->update($request,$id,$this->order);
            if(is_string($order)){
                    return response()->json(['status'=>false,'message'=>$order],404);
                }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

       

    }


    //methods for restoring
    public function restore($id){
        try{
            $order =  $this->orderRepo->restore($id,$this->order);
    
            if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
            }
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function restoreAll(){
        $orders =  $this->orderRepo->restoreAll($this->order);
        try{
            if(is_string($orders)){
                return response()->json(['status'=>false,'message'=>$orders],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$orders],200);

        
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
    public function destroy(DeleteOrderRequest $request,$id)
    {
        try{
            $order= $this->orderRepo->destroy($id,$this->order);
            if(is_string($order)){
                return response()->json(['status'=>false,'message'=>$order],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteOrderRequest $request,$id)
    {
        try{
        //to make force destroy for a Order must be this Order  not found in Orders table  , must be found in trash Orders
        $order=$this->orderRepo->forceDelete($id,$this->order);
     if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }

          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        
    

    
    


}
