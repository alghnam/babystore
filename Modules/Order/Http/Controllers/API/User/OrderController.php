<?php

namespace Modules\Order\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Entities\Cart;
use Modules\Coupon\Entities\Coupon;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Modules\UpSell\Entities\UpSell;
use Modules\Order\Http\Requests\DeleteOrderRequest;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Http\Requests\UpdateOrderRequest;
use DB;
use Modules\Order\Repositories\User\OrderRepository;
use Modules\Cart\Repositories\User\CartRepository;
use Modules\Order\Http\Requests\AddAddressRequest;
use Modules\Order\Http\Requests\UpdateAddressRequest;
use Modules\Order\Http\Requests\AddReviewOrderRequest;
use Modules\Order\Entities\Address;
use Modules\Order\Http\Requests\FinishingOrderRequest;
use Modules\Order\Http\Requests\ReFinishingOrderRequest;
use App\Http\Requests\Auth\CheckCodeRequest;
use Modules\Order\Entities\AddressCodeNum;
use Modules\Order\Entities\ReviewOrder;
use Modules\Wallet\Entities\Wallet;
use Modules\Movement\Entities\Movement;
use App\Models\TempDataUser;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use AmrShawky\LaravelCurrency\Facade\Currency;

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
     * @var CartRepository
     */
    protected $cartRepo;
   
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
    public function __construct(BaseRepository $baseRepo, Order $order,Cart $cart,Movement $movement, Address $address,OrderRepository $orderRepo,CartRepository $cartRepo, Coupon $coupon,AddressCodeNum $addressCodeNum,ReviewOrder $reviewOrder,Wallet $wallet)
    {
        $this->middleware(['permission:orders_show-data-user-address'])->only('showDataUserAddress','getAllAddressesUser');
        $this->middleware(['permission:orders_add'])->only('addOrder');
        $this->middleware(['permission:orders_add-address'])->only('addAddress');
        $this->middleware(['permission:orders_show-address'])->only('showAddress');
        $this->middleware(['permission:orders_resend'])->only('resendCode');
        $this->middleware(['permission:orders_code'])->only('checkCodeAddress');

        $this->middleware(['permission:orders_update-address'])->only('updateAddress');
        $this->middleware(['permission:orders_delete-address'])->only('deleteAddress');
        $this->middleware(['permission:orders_'])->only('finishingOrder');
        $this->middleware(['permission:orders_get-coupon-order'])->only('getCouponOrder');
        // $this->middleware(['permission:orders_get'])->only('getAllDataOrder');
        $this->middleware(['permission:orders_get-my-orders'])->only('myOrders');
        $this->middleware(['permission:orders_get-my-orders-status'])->only('myOrdersStatus');
        $this->middleware(['permission:orders_show-my-order'])->only('viewMyOrder');
        $this->middleware(['permission:orders_add-review-order'])->only('addReviewOrder');
        $this->middleware(['permission:orders_show-review-order'])->only('viewReviewOrder');
        
        
        
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
        $this->cartRepo = $cartRepo;

    }

    public function checkAndDecreaseFromStore(){
        return $this->orderRepo->checkAndDecreaseFromStore();
    }
    //for user
    public function showDataUserAddress()
    {
        try{
        $showDataUserAddress=$this->orderRepo->showDataUserAddress();
                if(is_string($showDataUserAddress)){
            return response()->json(['status'=>false,'message'=>$showDataUserAddress],400);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$showDataUserAddress],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function addOrder()
    {
        // try{
        $order= $this->orderRepo->addOrder($this->order);
                if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],400);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
       
        
    }
    public function addAddress(AddAddressRequest $request)
    {
        // try{
        $address=$this->orderRepo->addAddress($this->address,$request);
        if(is_string($address)){
            return response()->json(['status'=>false,'message'=>$address],400);
        }

            $user=auth()->guard('api')->user();
            $data=$request->validated();
            // Delete all old code that user send before.
            AddressCodeNum::where('phone_no', $data['phone_no'])->delete();
            $code=mt_rand(1000, 9999);
             $phone_no=$data['phone_no'];
            Storage::put($user->id.'-phone_no_address',$phone_no);
            Storage::put($user->id.'-address_id',$address->id);
            $TempDataUser= TempDataUser::where(['user_id'=>$user->id])->first();
              if(empty($TempDataUser)){
                 $TempDataUser=new TempDataUser();
                 $TempDataUser->user_id=$user->id;
                $TempDataUser->phone_no_address=$phone_no;
                  $TempDataUser->save();
              }else{
                $TempDataUser->phone_no_address=$phone_no;

                  $TempDataUser->save();
              }
            //insert code 
            AddressCodeNum::insert(['code'=>$code,'phone_no'=>$phone_no,'address_id'=>$address->id]);
         // Send sms to phone
       // $this->smsRepo->send($code,$phone_no);
            $data=[
                'code'=>$code,
                'address'=>$address
            ];
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
        
    }
    public function resendCode($addressId,$phone_no){
        try{
        $user=auth()->guard('api')->user();

         // Delete all old code that user send before.
           $addressCode= AddressCodeNum::where(['phone_no'=> $phone_no,'address_id'=>$addressId])->first();
           if($addressCode){
               
           $addressCode->delete();
           }
        $code=mt_rand(1000, 9999);

        //insert code 
        AddressCodeNum::insert(['code'=>$code,'phone_no'=> $phone_no,'address_id'=>$addressId]);
                    Storage::put($user->id.'-phone_no_address',$phone_no);
            Storage::put($user->id.'-address_id',$addressId);
         // Send sms to phone
       // $this->smsRepo->send($code,$phone_no_address);
            $data=[
                'code'=>$code,
                'phone'=>$phone_no,
                'addressId'=>$addressId
            ];
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function checkCodeAddress(CheckCodeRequest $request){
        try{
            $code=$this->orderRepo->checkCode($request,$this->addressCodeNum);
            if(is_string($code)){
                return response()->json(['status'=>false,'message'=>$code],400);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$code],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function showAddress($addressId)
    {
        // try{
            $address=$this->orderRepo->showAddress($addressId,$this->address);
            if(is_string($address)){
                return response()->json(['status'=>false,'message'=>$address],404);
            }
            $user=auth()->guard('api')->user();
            $data=[
                'user'=>$user,
                'address'=>$address
            ];
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 

        
    }
    public function getAllAddressesUser(){
        try{
        $getAllAddressesUser=$this->orderRepo->getAllAddressesUser($this->address);

           // $user=auth()->guard('api')->user();
            $data=[
               // 'user'=>$user,
                'addresses'=>$getAllAddressesUser
            ];
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
    }
    public function updateAddress($addressId,UpdateAddressRequest $request)
    {
        //try{
        $address=$this->orderRepo->updateAddress($addressId,$this->address,$request);
        if(is_string($address)){
            return response()->json(['status'=>false,'message'=>$address],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$address],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
       
        }
        
    
    
    public function deleteAddress($addressId)
    {
        //try{
        $address= $this->orderRepo->deleteAddress($addressId,$this->address);
                if(is_string($address)){
            return response()->json(['status'=>false,'message'=>$address],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$address],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }
    

    public function paymentProcess($totalPrice,$orderId){
                $paymentProcess=$this->orderRepo->paymentProcess($totalPrice,$orderId);
        return $paymentProcess;
    }
    
    public function paymentCallbackFinishing(Request $request,$orderId,$totalPriceBill,$userId){
                // try{

        $paymentProcess=$this->orderRepo->paymentCallback($request,$orderId);
        if($paymentProcess&&isset($paymentProcess->errors)){
            return response()->json(['status'=>false,'message'=>$paymentProcess],400);
             }
                    $cardName=null;
                if($paymentProcess->status=="CAPTURED"){
                    if(isset($paymentProcess->card)){
                        $cardName='فيزا';
                    }else{
                        $cardName='كي نت';
                    }
                    
            //  $cardName=$paymentProcess->card->scheme;

                  $order=Order::where('id',$orderId)->first();
                    $cart=Cart::where('user_id',$userId)->first();
                    $wallet=Wallet::where('user_id',$userId)->first();
                     if(empty($wallet)){// 
                    $wallet=new Wallet;
                    $wallet->user_id=$userId;
                    $wallet->save();
                }  
                    $coupon_id= Storage::get($userId.'-coupon_id');
                    $service_id= Storage::get($userId.'-service_id');
                    $address_id= Storage::get($userId.'-address_id');
                    $payment_id= Storage::get($userId.'-payment_id');
                    $this->orderRepo->processAfterPayment($userId,$wallet,$order,$cart,$totalPriceBill,$coupon_id,$payment_id,$service_id,$address_id,$status=1,$type=3,$cardName);
                    $coupon= Coupon::where(['id'=>$coupon_id])->first();
                            $location = geoip(request()->ip());

                    if($location->currency!==config('constants.currency_system')){

                        if($order->price){
                            $convertingPrice =  $this->baseRepo->priceCalculation($order->price);
                            $order->price=$convertingPrice;
                        }
                        if($order->service){
                            $convertingValueService =  $this->baseRepo->priceCalculation($order->service->value);
                            $order->service->value=$convertingValueService;
                        }
                         if($coupon){
                            $convertingValueCoupon =  $this->baseRepo->priceCalculation($coupon->value);
                            $coupon->value=$convertingValueCoupon;
                        
                         }
                         if($order->coupon){
                            $convertingValuecouponE =  $this->baseRepo->priceCalculation($order->coupon->value);
                            $order->coupon->value=$convertingValuecouponE;
                        }
                    }
        
                           $arrUpsellsPro=[];
                    $productsOrder=[];
                    if(count($order->productArrayAttributes)){
                        $productsOrder=$order->productArrayAttributes()->with('product')->get();
                        $location = geoip(request()->ip());

                        if($location->currency!==config('constants.currency_system')){

                            foreach($productsOrder as $productOrder){
                            if($productOrder->pivot->quantity!==0){
                                if($productOrder->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($productOrder->original_price);
                                    $productOrder->original_price=$convertingOriginalPrice;
                                }
                                if($productOrder->price_discount_ends){
                                    $convertingPriceEnds =  $this->baseRepo->priceCalculation($productOrder->price_discount_ends);
                                    $productOrder->price_discount_ends=$convertingPriceEnds;
                                }
                                 if($productOrder->product->original_price){
                                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($productOrder->product->original_price);
                                        $productOrder->product->original_price=$convertingOriginalPrice;
                                    }
                                if($productOrder->product->price_discount_ends){
                                    $convertingPriceEnds =  $this->baseRepo->priceCalculation($productOrder->product->price_discount_ends);
                                    $productOrder->product->price_discount_ends=$convertingPriceEnds;
                                }
                              $upsellsPro= UpSell::where(['product_id'=>$productOrder->product_id])->first();
                                if(!empty($upsellsPro)){
                                    $upsells=$upsellsPro->upsells;
                                      foreach($upsells as $upsellPro){
                                         $product= Product::where(['id'=>$upsellPro])->first();
                                         if(!empty($product)){
                                            array_push($arrUpsellsPro,$product->load(['productImages','productArrayAttributes']));
                                             
                                         }else{
                                            //if this id in arr upsells not exist in products table , will delete it in all get for it , becuase not found in table products
                                            array_splice($upsells, array_search($upsellPro, $upsells ), 1);
                                            $upsellsPro->upsells=$upsells;
                                            $upsellsPro->save();
                                         }
            
                                        
                                      }
                                }
                            }
                       }
                        }
                    }
                    $data=[
                        'order'=>$order->load(['payment']),
                        'coupon'=>$coupon,
                        'products'=>$productsOrder,
                        'upsells'=>$arrUpsellsPro
                        ];
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);
                }else{
                    
                   
                    return response()->json(['status'=>false,'message'=>'فشلت العملية'],400);

                }

            
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
        
    }
    
    public function finishingOrder(FinishingOrderRequest $request){
        // try{

       $data=$request->validated();
        $order=$this->orderRepo->finishingOrder($this->order,$this->wallet,$this->movement,$request);
        if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
        if((!empty($data['payment_id'])&&$data['payment_id']==3)||(!empty($data['payment_id'])&&$data['payment_id']==4)){//payment method
        
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);
        }
        $user=auth()->guard('api')->user();

        $arrUpsellsPro=[];
        $productsOrder=[];
        $orderFinished=Order::where(['id'=>$data['order_id']])->first();
        $orderFinished->currency_country=$this->baseRepo->countryCurrency();
        $location = geoip(request()->ip());
        if($location->currency!==config('constants.currency_system')){

            if($orderFinished->price){
                $convertingPrice =  $this->baseRepo->priceCalculation($orderFinished->price);
                $orderFinished->price=$convertingPrice;
            }
           
            if($orderFinished->service){
                $convertingValueService =  $this->baseRepo->priceCalculation($orderFinished->service->value);
                $orderFinished->service->value=$convertingValueService;
            }
             if($orderFinished->coupon){
                $convertingValueCoupon =  $this->baseRepo->priceCalculation($orderFinished->coupon->value);
                $orderFinished->coupon->value=$convertingValueCoupon;
            
             }
        }
        if(count($orderFinished->productArrayAttributes)){
            $productsOrder=$orderFinished->productArrayAttributes()->with('product')->get();
            $productsOrder->currency_country=$this->baseRepo->countryCurrency();
                if($location->currency!==config('constants.currency_system')){
                  //convert this price that in dinar into currency user
                        
                        foreach($productsOrder as $proo){
                            if($proo->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($proo->original_price);
                                    $proo->original_price=$convertingOriginalPrice;
                                }
                            if($proo->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($proo->price_discount_ends);
                                $proo->price_discount_ends=$convertingPriceEnds;
                            }
                             if($proo->product->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($proo->product->original_price);
                                    $proo->product->original_price=$convertingOriginalPrice;
                                }
                            if($proo->product->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($proo->product->price_discount_ends);
                                $proo->product->price_discount_ends=$convertingPriceEnds;
                            }
                        }

                }
           foreach($orderFinished->productArrayAttributes as $productOrder){

                if($productOrder->pivot->quantity!==0){
                  $upsellsPro= UpSell::where(['product_id'=>$productOrder->product_id])->first();
                    if(!empty($upsellsPro)){
                        $upsells=$upsellsPro->upsells;
                          foreach($upsells as $upsellPro){
                             $product= Product::where(['id'=>$upsellPro])->first();
                             if(!empty($product)){
                                array_push($arrUpsellsPro,$product->load(['productImages','productArrayAttributes']));
                                 
                             }else{
                                //if this id in arr upsells not exist in products table , will delete it in all get for it , becuase not found in table products
                                array_splice($upsells, array_search($upsellPro, $upsells ), 1);
                                $upsellsPro->upsells=$upsells;
                                $upsellsPro->save();
                             }

                            
                          }
                    }
                }
           }
        }

        if(!empty($data['coupon_id'])){
            
       $coupon= Coupon::where(['id'=>$data['coupon_id']])->first();
        $data=[
            'order'=>$orderFinished->load(['payment']),
            'coupon'=>$coupon,
            'products'=>$productsOrder,
            'upsells'=>$arrUpsellsPro
            ];
        }else{
             $data=[
            'order'=>$orderFinished->load(['payment']),
            'coupon'=>null,
            'products'=>$productsOrder,
            'upsells'=>$arrUpsellsPro
            ];
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }


    public function paymentCallbackReFinishing(Request $request,$orderId,$totalPrice,$userId){
                // try{

        $paymentProcess=$this->orderRepo->paymentCallback($request,$orderId);
        if($paymentProcess&&isset($paymentProcess->errors)){
            return response()->json(['status'=>false,'message'=>$paymentProcess],400);
             }
                if($paymentProcess->status=="CAPTURED"){
             $cardName=$paymentProcess->card->scheme;

                 $order=Order::where('id',$orderId)->first();
                    $cart=Cart::where('user_id',$userId)->first();
                    $wallet=Wallet::where('user_id',$userId)->first();
                     if(empty($wallet)){// 
                    $wallet=new Wallet;
                    $wallet->user_id=$userId;
                    $wallet->save();
                }  
                    $payment_id= Storage::get($userId.'-payment_id-re');
                    $product_array_attributes= Storage::get($userId.'-product_array_attributes-re');
                    $productsOrder=$order->productArrayAttributes()->with('product')->get();
                        if($productsOrder->products){
                            foreach($productsOrder->products as $proo){
                             if($proo->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($proo->original_price);
                                    $proo->original_price=$convertingOriginalPrice;
                                }
                            if($proo->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($proo->price_discount_ends);
                                $proo->price_discount_ends=$convertingPriceEnds;
                            }
                            
                        }
                    }
                    $this->orderRepo->processAfterPaymentForRefinishing($wallet,$order,$totalPrice,$payment_id,$product_array_attributes,$status=1,$type=3);
                   
                    $data=[
                        'order'=>$order,
                        'products'=>$productsOrder,
                        'process'=>$paymentProcess
                        ];
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);
                }else{
                   
                    return response()->json(['status'=>false,'message'=>'فشلت العملية'],400);

                }

            
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
        
    }

        public function reFinishingOrder(ReFinishingOrderRequest $request,$orderId){
            // try{
                $data=$request->validated();

                $order=$this->orderRepo->reFinishingOrder($this->order,$this->wallet,$this->movement,$request,$orderId);
                $user=auth()->guard('api')->user();
                 if(!empty($data['payment_id'])&&$data['payment_id']==3){//payment method
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);
                }
                if(is_string($order)){
                    return response()->json(['status'=>false,'message'=>$order],400);
                }
                        $orderFinished=Order::where(['id'=>$orderId])->first();
                                $orderFinished->currency_country=$this->baseRepo->countryCurrency();
        $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){

            if($orderFinished->price){
                $convertingPrice =  $this->baseRepo->priceCalculation($orderFinished->price);
                $orderFinished->price=$convertingPrice;
            }
            if($orderFinished->service){
                $convertingValueService =  $this->baseRepo->priceCalculation($orderFinished->service->value);
                $orderFinished->service->value=$convertingValueService;
            }
             if($orderFinished->coupon){
                $convertingValueCoupon =  $this->baseRepo->priceCalculation($orderFinished->coupon->value);
                $orderFinished->coupon->value=$convertingValueCoupon;
            
             }
        }
                $productsOrder=$order->productArrayAttributes()->with('product')->get();

                  if($location->currency!==config('constants.currency_system')){
                  //convert this price that in dinar into currency user
                        foreach($productsOrder as $proo){
                            if($proo->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($proo->original_price);
                                    $proo->original_price=$convertingOriginalPrice;
                                }
                            if($proo->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($proo->price_discount_ends);
                                $proo->price_discount_ends=$convertingPriceEnds;
                            }
                             if($proo->product->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($proo->product->original_price);
                                    $proo->product->original_price=$convertingOriginalPrice;
                                }
                            if($proo->product->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($proo->product->price_discount_ends);
                                $proo->product->price_discount_ends=$convertingPriceEnds;
                            }
                        }

                            
                           
                        
                }
                $data=[
                    'order'=>$orderFinished->load(['payment']),
                    'products'=>$productsOrder
                ];
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
        }
    public function cancelOrder($orderId){
        // try{
            $order=$this->orderRepo->cancelOrder($orderId,$this->order);
             if(is_string($order)){
                    return response()->json(['status'=>false,'message'=>$order],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // }
       
    }
    //لازم يكون مدخل بالانبوتس عشان تطلعلي نتيجة بالايدي 
    //فتاع التطبيق بس عليه بالاول يختاروسيلة الدفع ويضغط ع انهاء الطلب بيوديه اللي هو هلا صار متخزن ايدي تاع الوسيلة اللي حيتحمل هنا ع هاددا الراوت اللي حيستدعى من الكارد اللي حتظهرله بعد م يضعط ع انهاء الطلب 
    //حيعبي اللي فيها ويضغط ع دفع الان ليشوف النتيجة 
    
    public function getPaymentStatus($id){
                                  $user=auth()->guard('api')->user();

            	 Storage::put($user->id.'-paymentStatusId',null);
            	   $TempDataUser= TempDataUser::where(['user_id'=>$user->id])->first();
              if(empty($TempDataUser)){
                 $TempDataUser=new TempDataUser();
                 $TempDataUser->user_id=$user->id;
                $TempDataUser->payment_status_id=null;
                  $TempDataUser->save();
              }else{
                $TempDataUser->payment_status_id=null;

                  $TempDataUser->save();
              }

        $url = "https://eu-test.oppwa.com/v1/checkouts/".$id."/payment";
        $url .= "?entityId=8a8294174b7ecb28014b9699220015ca";
        
        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                           'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	$responseData = curl_exec($ch);
        	if(curl_errno($ch)) {
        		return curl_error($ch);
        	}
        	curl_close($ch);
    	$paymentStatus= json_decode($responseData,true);
    	return $paymentStatus;
    	  //store id in db from $paymentStatus 
      //$order->this order that click on it to finishing itfrom method finishingOrder
    //             $orderIdFinished=  Storage::get('orderIdFinished');
    //     $order=Order::where(['id'=>$orderIdFinished])->first();
    //   $order->bank_transaction_id=$paymentStatus['id'];
    //   $order->save();
    	 if(isset($paymentStatus['id'])){//payment success
    	 Storage::put($user->id.'-paymentStatusId',$paymentStatus['id']);
    	     $TempDataUser= TempDataUser::where(['user_id'=>$user->id])->first();
              if(empty($TempDataUser)){
                 $TempDataUser=new TempDataUser();
                 $TempDataUser->user_id=$user->id;
                $TempDataUser->payment_status_id=$paymentStatus['id'];
                  $TempDataUser->save();
              }else{
                $TempDataUser->payment_status_id=$paymentStatus['id'];

                  $TempDataUser->save();
              }
            return true;
            
        }else{
            return false;
        }
    	
    
       
    }
    public function getCouponOrder(){
        try{
        $coupon=$this->orderRepo->getCouponOrder($this->order);
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$coupon],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }    
    }
    public function getAllDataOrder(){
        try{
                                  $user=auth()->guard('api')->user();

        $couponOrder=Storage::get($user->id.'-coupon_value');
        if(empty($couponOrder)){
            
            return response()->json(['status'=>false,'message'=>'عليك ان تضع كوبون الخصم اولا '],400);
        }
        
        $cartorderProducts=$this->cartRepo->getCartUser($this->cart);
  
        //decrease price coupon if found , from total price order
        $couponOrder=Storage::get($user->id.'-coupon_name');
        $couponValue=null;
        if($couponOrder){
            $coupon=  Coupon::where('name',$couponOrder)->first();
            $couponValue=$coupon->coupon_value;
        }
        $totalPriceProducts=0;
           foreach($cartorderProducts->products as $cartorderProduct){
               $totalPriceProducts=($totalPriceProducts+($cartorderProduct->price_discount_ends*$cartorderProduct->quantity));
           }
           $totalPriceOrder=$totalPriceProducts-$couponValue;
        $data=[
            'total price products cart'=>$totalPriceProducts,
            'coupon'=>$couponValue,
            'total price order'=>$totalPriceOrder
        ];
    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

///////////
public function myOrders(Request $request){
    // try{
    $myOrders=$this->orderRepo->myOrders($this->order,$request);
            if(is_string($myOrders)){
            return response()->json(['status'=>false,'message'=>$myOrders],404);
        }

    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$myOrders],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
}
 
 public function myOrdersStatus($status,Request $request){
    //  try{
    $myOrders=$this->orderRepo->myOrdersStatus($this->order,$status,$request);
        if(is_string($myOrders)){
            return response()->json(['status'=>false,'message'=>$myOrders],404);
        }
        
    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$myOrders],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
}



 public function viewMyOrder($id)
    {
        // try{
        $order=$this->orderRepo->viewMyOrder($id,$this->order);
        if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
              $location = geoip(request()->ip());
            $currencyCountry=$location->currency;
            $order->currency_country=$currencyCountry;
                $currencySystem='KWD';
            if($location->currency!==$currencySystem){
                //convert this price that in dinar into currency user
                    $convertingPrice=  Currency::convert()
                        ->from($currencySystem)
                        ->to($currencyCountry)
                        ->amount($order->price)
                        ->get();
                    $order->price=round($convertingPrice,2);
                
                foreach($order->productArrayAttributes as $attr){
                    $convertingOriginalPriceAttr=  Currency::convert()
                        ->from($currencySystem)
                        ->to($currencyCountry)
                        ->amount($attr->original_price)
                        ->get();
                
                    $attr->original_price=round($convertingOriginalPriceAttr,2);
                    
                    $convertingPriceEndsAttr=  Currency::convert()
                        ->from($currencySystem)
                        ->to($currencyCountry)
                        ->amount($attr->price_discount_ends)
                        ->get();
                
                    $attr->price_discount_ends=round($convertingPriceEndsAttr,2);
                }
            
            }
         
    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
            
            
    }
    
    public function addReviewOrder($orderId,AddReviewOrderRequest $request){
        try{
           $order=$this->orderRepo->addReviewOrder($orderId,$this->reviewOrder,$request);
                   if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],400);
        }
            $data=[
                'review'=>$order,
                'image'=>$order->load('image')
                ];
    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
            
            
        
    }
    
    public function viewReviewOrder($orderId){
        try{
        $order=$this->orderRepo->viewReviewOrder($orderId,$this->reviewOrder);
                           if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$order],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
            
    }

}
