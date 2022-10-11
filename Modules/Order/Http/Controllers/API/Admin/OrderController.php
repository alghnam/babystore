<?php

namespace Modules\Order\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Entities\Cart;
use Modules\Coupon\Entities\Coupon;
// use Modules\Coupon\Repositories\Admin\CouponRepository;
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
use App\Http\Requests\Auth\CheckCodeRequest;
use Modules\Order\Entities\AddressCodeNum;
use Modules\Order\Entities\ReviewOrder;
use Modules\Movement\Entities\Movement;
use Modules\Wallet\Entities\Wallet;
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
     * @var CouponRepository
     */
    protected $couponRepo;
        /**
     * @var Order
     */
    protected $order;
            /**
     * @var ReviewOrder
     */
    protected $reviewOrder;
            /**
     * @var Cart
     */
    protected $cart;
                /**
     * @var Coupon
     */
    protected $coupon;
   
           /**
     * @var Address
     */
    protected $address;
    
       
           /**
     * @var Wallet
     */
    protected $wallet;
    
       
           /**
     * @var Movement
     */
    protected $movement;

    /**
     * OrdersController constructor.
     *
     * @param OrderRepository $orders
     */
    public function __construct(BaseRepository $baseRepo, Order $order,Cart $cart, Address $address,OrderRepository $orderRepo,Coupon $coupon,AddressCodeNum $addressCodeNum,ReviewOrder $reviewOrder,Movement $movement,Wallet $wallet)
    {
        // $this->middleware(['permission:orders_read'])->only(['index','getAllPaginates']);
        // $this->middleware(['permission:orders_trash'])->only('trash');
        // $this->middleware(['permission:orders_restore'])->only('restore');
        // $this->middleware(['permission:orders_restore-all'])->only('restore-all');
        // $this->middleware(['permission:orders_show'])->only('show');
        // $this->middleware(['permission:orders_store'])->only('store');
        // $this->middleware(['permission:orders_update'])->only('update');
        // $this->middleware(['permission:orders_destroy'])->only('destroy');
        // $this->middleware(['permission:orders_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->order = $order;
        $this->addressCodeNum = $addressCodeNum;
        $this->cart = $cart;
        $this->coupon = $coupon;
        $this->address = $address;
        $this->wallet = $wallet;
        $this->movement = $movement;
        $this->reviewOrder = $reviewOrder;
        $this->orderRepo = $orderRepo;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
        $orders=$this->orderRepo->all($this->order);
        

        if(is_string($orders)){
            return response()->json(['status'=>false,'message'=>$orders],400);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'تم ايجاد البيانات بنجاح',
            'data'=> $orders
        ]);
    }
        public function getAllPaginates(Request $request){
            try{
                
                $orders=$this->orderRepo->getAllPaginates($this->order,$request);
             if(is_string($orders)){
                    return response()->json(['status'=>false,'message'=>$orders],400);
                }
            return response()->json([
                'status'=>true,
                'message' => 'تم ايجاد البيانات بنجاح',
                'data'=> $orders
            ],200);
                    
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

   public function getOrdersForCategory($categoryId){
       $getOrdersForCategory=$this->orderRepo->getOrdersForCategory($this->order,$categoryId);
if(is_string($orders)){
            return response()->json(['status'=>false,'message'=>$orders],400);
        }
    return response()->json([
        'status'=>true,
        'message' => 'تم ايجاد البيانات بنجاح',
        'data'=> $orders
    ],200);
   }




    // methods for trash
    public function trash(Request $request){
        $orders=$this->orderRepo->trash($this->order,$request);
if(is_string($orders)){
            return response()->json(['status'=>false,'message'=>$orders],400);
        }

    return response()->json([
        'status'=>true,
        'message' => 'تم ايجاد البيانات بنجاح',
        'data'=> $orders
    ],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $order=$this->orderRepo->store($request,$this->order);
if(is_string($orders)){
            return response()->json(['status'=>false,'message'=>$orders],400);
        }
    return response()->json([
        'status'=>true,
        'message' => 'تم التخزين بنجاح',
        'data'=> $order
    ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order=$this->orderRepo->find($id,$this->order);
        if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
   
        return response()->json([
            'status'=>true,
            'message' => 'تم ايجاد البيانات بنجاح',
            'data'=> $order
        ],200);
        
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
       $order= $this->orderRepo->update($request,$id,$this->order);



    if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
    return response()->json([
        'status'=>true,
        'message' => 'تم التعديل بنجاح',
        'data'=> $order
    ],200);
    

       

    }


    //methods for restoring
    public function restore($id){
        
        $order =  $this->orderRepo->restore($id,$this->order);
    
     if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
    
            return response()->json([
                'status'=>true,
                'message' => 'تمت الاستعادة بنجاح',
                'data'=> $order
            ],200);
    }
    public function restoreAll(){
        $orders =  $this->orderRepo->restoreAll($this->order);
     if(is_string($orders)){
            return response()->json(['status'=>false,'message'=>$orders],404);
        }
        return response()->json([
            'status'=>true,
            'message' => 'تم استعادة الكل بنجاح',
            'data'=> $orders
        ],200);
        

    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteOrderRequest $request,$id)
    {
       $order= $this->orderRepo->destroy($id,$this->order);
                                  if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
        return response()->json([
            'status'=>true,
            'message' => 'تم الحذف بنجاح',
            'data'=> $order
        ]); 
       
    }
    public function forceDelete(DeleteOrderRequest $request,$id)
    {
        //to make force destroy for a Order must be this Order  not found in Orders table  , must be found in trash Orders
        $order=$this->orderRepo->forceDelete($id,$this->order);
     if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }

        return response()->json([
            'status'=>true,
            'message' => 'تم الحذف بنجاح',
            'data'=> null
        ],200); 
    

    }
    

}
