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
use Modules\Movement\Entities\Movement;
use Modules\Wallet\Entities\Wallet;
use App\Models\TempDataUser;
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
    public function __construct(BaseRepository $baseRepo, Order $order,Cart $cart, Address $address,OrderRepository $orderRepo,CartRepository $cartRepo, Coupon $coupon,AddressCodeNum $addressCodeNum,ReviewOrder $reviewOrder,Movement $movement,Wallet $wallet)
    {
        $this->middleware(['permission:orders_show-data-user-address'])->only('showDataUserAddress');
        $this->middleware(['permission:orders_add'])->only('addOrder');
        $this->middleware(['permission:orders_add-address'])->only('addAddress');
        $this->middleware(['permission:orders_show-address'])->only('showAddress');
        $this->middleware(['permission:orders_resend'])->only('resendCode');
        $this->middleware(['permission:orders_code'])->only('checkCodeAddress');
        $this->middleware(['permission:orders_get-addresses'])->only('getAllAddressesUser');
        $this->middleware(['permission:orders_update-address'])->only('updateAddress');
        $this->middleware(['permission:orders_delete-address'])->only('deleteAddress');
        // $this->middleware(['permission:orders_finish'])->only('finishingOrder');
        $this->middleware(['permission:orders_get-coupon-order'])->only('getCouponOrder');
        $this->middleware(['permission:orders_get'])->only('getAllDataOrder');
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

    
    //for user
    public function showDataUserAddress()
    {
        $showDataUserAddress=$this->orderRepo->showDataUserAddress();
                if(is_string($showDataUserAddress)){
            return response()->json(['status'=>false,'message'=>$showDataUserAddress],400);
        }
        return response()->json([
            'status'=>true,
            'message' => 'data address user has been getten successfully',
            'data'=> $showDataUserAddress
        ]);
    }
    public function addOrder()
    {
        $order= $this->orderRepo->addOrder($this->order);
                if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],400);
        }
        if(is_object($order)){
            return response()->json([
            'status'=>true,
            'message' => 'Order has been stored successfully',
            'data'=> $order
        ]);
        }
       
        
    }
    public function addAddress(AddAddressRequest $request)
    {
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
            return response()->json([
                'status'=>true,
                'message' => 'address has been stored successfully',
                'data'=> $data
            ]);
        
    }
    public function resendCode($addressId,$phone_no){
                                  $user=auth()->guard('api')->user();

//   $phone_no_address= Storage::get($user->id.'-phone_no_address');
//   if($phone_no_address==null){
//                 //   return response()->json(['status'=>false,'message'=>'please, check if you written phone_no after that click on add address button or not'],400);
//                   return response()->json(['status'=>false,'message'=>'من فضلك , اكتب رقم الموبايل قبل الضغط على زر اضافة عنوان'],400);

//   }

         // Delete all old code that user send before.
           $addressCode= AddressCodeNum::where(['phone_no'=> $phone_no,'address_id'=>$addressId])->first();
           if($addressCode){
               
           $addressCode->delete();
           }
        $code=mt_rand(1000, 9999);

        //insert code 
        AddressCodeNum::insert(['code'=>$code,'phone_no'=> $phone_no,'address_id'=>$addressId]);
         // Send sms to phone
       // $this->smsRepo->send($code,$phone_no_address);
            $data=[
                'code'=>$code,
                'phone'=>$phone_no,
                'addressId'=>$addressId
            ];
            return response()->json([
                'status'=>true,
                'message' => 'code has been sent again successfully',
                'data'=> $data
            ]);
    }
    public function checkCodeAddress(CheckCodeRequest $request){
        $code=$this->orderRepo->checkCode($request,$this->addressCodeNum);
        if(is_string($code)){
            return response()->json(['status'=>false,'message'=>$code],400);
        }
            return response()->json([
                'status'=>true,
                'message' => 'code is valid ',
                'data'=> $code
            ]);
       
    }
    public function showAddress($addressId)
    {

        $address=$this->orderRepo->showAddress($addressId,$this->address);
        if(is_string($address)){
            return response()->json(['status'=>false,'message'=>$address],404);
        }
            $user=auth()->guard('api')->user();
            $data=[
                'user'=>$user,
                'address'=>$address
            ];
            return response()->json([
                'status'=>true,
                'message' => 'address has been getten successfully',
                'data'=> $data
            ]);

        
    }
    public function getAllAddressesUser(){
        $getAllAddressesUser=$this->orderRepo->getAllAddressesUser($this->address);

           // $user=auth()->guard('api')->user();
            $data=[
               // 'user'=>$user,
                'addresses'=>$getAllAddressesUser
            ];
            return response()->json([
                'status'=>true,
                'message' => 'addresses has been getten successfully',
                'data'=> $data
            ]);

        
    }
    public function updateAddress($addressId,UpdateAddressRequest $request)
    {
        $address=$this->orderRepo->updateAddress($addressId,$this->address,$request);
        if(is_string($address)){
            return response()->json(['status'=>false,'message'=>$address],404);
        }
            return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'address has been updated successfully',
            'data'=> $address
        ]);
       
        }
        
    
    
    public function deleteAddress($addressId)
    {
        $address= $this->orderRepo->deleteAddress($addressId,$this->address);
                if(is_string($address)){
            return response()->json(['status'=>false,'message'=>$address],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'address user has been deleted successfully',
            'data'=> null
        ]);
    }

    public function finishingOrder(FinishingOrderRequest $request){//لما يضغط ع انهائ الطلب ولو عن طريق دفع معين بتظهر تاعة الدفع وبعبي بينات البطاقة وبضغط ادفع الان اي بنادي ع راوت جيت بيمنت ستيتس اللي بيدخلله الايدي اللي طلع من هان وبس 
    //اي راوت تاع انهاء الطلب ومنه بتااخد الايدي يدخل بروات جيت بيمنت ستيتس ليكتمل الدفع 
       $data=$request->validated();
        $order=$this->orderRepo->finishingOrder($this->order,$this->wallet,$this->movement,$request);
        
                                $user=auth()->guard('api')->user();

        //ادا كان مختار الميثود 2 اي فيزا وليس محفظة بالتالي خيتم استدعاء الايباي الخاص بالفيزا والربسبونس فيه ايدي 
        //هادا الايدي اضل مايكاه وتاع التطبيق من اول م يشوف انو بالريسبونش في ايدي وهيك اي عملية الدفع ماشية تمام فبيظهر فورم الفيزا متلا  والمفروض بيعبي البيانات بالانبوتس تاعة الفيزا ويضغط ع زر دفع الان 
        //ولما ينضغط عالزر تاع دفع بيطلع ريسبونس منه ببعتلي اشي اسمه ريسبونس باث عشان امسكه وامرره بالبينمنت ستيست   
        //ادن الريسبونس باث والايدي هم اللي حيحطهم بالراوت اللي لما يضغط ع ادفع الان 

       // return $order;//بداخله ايدي التشيك اووت 
    //   $paymentStatus= $this->getPaymentStatus($order->id,$resourcePath);//تم العملية بنجاح او لا 
    //   //store id in db from $paymentStatus 
    //   //$order->from return res
    //   $order->bank_transaction_id=$paymentStatus['id'];
    //   $order->save();
    //     if(isset($paymentStatus['id'])){//payment success
    //         return true;
            
    //     }else{
    //         return false;
    //     }
              if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],400);
        }                     
        $orderFinished=Order::where(['id'=>$data['order_id']])->first();
        $cartorderProducts=$this->cartRepo->getCartUser($this->cart);
        $totalPriceProducts=0;
        if(count($cartorderProducts->productArrayAttributes)){
            
           foreach($cartorderProducts->productArrayAttributes as $cartorderProduct){
               $totalPriceProducts=$totalPriceProducts+($cartorderProduct->price_discount_ends*$cartorderProduct->quantity);
                $arrUpsellsPro=[];
                if($cartorderProduct->quantity!==0){
                    
                  $upsellsPro= UpSell::where(['product_id'=>$cartorderProduct->product_id])->get();
                    if(count($upsellsPro)!==0){
                          foreach($upsellsPro as $upsellPro){
                             $product= Product::where(['id'=>$upsellPro->product_id])->first();
                                                            array_push($arrUpsellsPro,$product->load(['productImages','productArrayAttributes']));

                            
                          }
                    }
                }
           }
        }        
        if(!empty($data['coupon_id'])){
            
       $coupon= Coupon::where(['id'=>$data['coupon_id']])->first();
        $data=[
            'order'=>$orderFinished->load(['payment','service']),
            'coupon'=>$coupon,
            'products'=>$cartorderProducts,
            'upsells'=>$arrUpsellsPro
            ];
        }else{
             $data=[
            'order'=>$orderFinished->load(['payment','service']),
            'coupon'=>null,
            'products'=>$cartorderProducts,
            'upsells'=>$arrUpsellsPro
            ];
        }
            return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'Order has been stored successfully',
            'data'=> $data
        ]);
    }
    
    
        public function reFinishingOrder(ReFinishingOrderRequest $request,$orderId){//لما يضغط ع انهائ الطلب ولو عن طريق دفع معين بتظهر تاعة الدفع وبعبي بينات البطاقة وبضغط ادفع الان اي بنادي ع راوت جيت بيمنت ستيتس اللي بيدخلله الايدي اللي طلع من هان وبس 
   
    //اي راوت تاع انهاء الطلب ومنه بتااخد الايدي يدخل بروات جيت بيمنت ستيتس ليكتمل الدفع 
                       $data=$request->validated();

        $order=$this->orderRepo->reFinishingOrder($this->order,$this->wallet,$this->movement,$request,$orderId);
        $user=auth()->guard('api')->user();

        if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],400);
        }
                $orderFinished=Order::where(['id'=>$orderId])->first();

        $data=[
            'order'=>$orderFinished
        ];
            return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'Order has been stored successfully',
            'data'=> $data
        ]);
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
        $coupon=$this->orderRepo->getCouponOrder($this->order);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'Coupon has been getten successfully',
            'data'=> $coupon
        ]);    
    }
    public function getAllDataOrder(){
                                  $user=auth()->guard('api')->user();

        $couponOrder=Storage::get($user->id.'-coupon_value');
            return response()->json(['status'=>false,'message'=>'عليك ان تضع كوبون الخصم اولا '],400);
        
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
        return response()->json([
            'status'=>true,
            'message' => 'order has been getten successfully',
            'data'=> $data
        ]);
    }

///////////
public function myOrders(){
    $myOrders=$this->orderRepo->myOrders($this->order);
            if(is_string($myOrders)){
            return response()->json(['status'=>false,'message'=>$myOrders],404);
        }

     return response()->json([
            'status'=>true,
            'message' => 'order has been getten successfully',
            'data'=> $myOrders
        ]);
}
 
 public function myOrdersStatus($status){
    $myOrders=$this->orderRepo->myOrdersStatus($this->order,$status);
        if(is_string($myOrders)){
            return response()->json(['status'=>false,'message'=>$myOrders],404);
        }
     return response()->json([
            'status'=>true,
            'message' => 'order has been getten successfully',
            'data'=> $myOrders
        ]);
}



 public function viewMyOrder($id)
    {
        $order=$this->orderRepo->viewMyOrder($id,$this->order);
                    if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
            return response()->json([
                'status'=>true,
                'message' => 'Order has been getten successfully',
                'data'=> $order
            ]);
            
            
    }
    
    public function addReviewOrder($orderId,AddReviewOrderRequest $request){
           $order=$this->orderRepo->addReviewOrder($orderId,$this->reviewOrder,$request);
                   if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],400);
        }
            $data=[
                'review'=>$order,
                'image'=>$order->load('image')
                ];
            return response()->json([
                'status'=>true,
                'message' => 'review for this order has been added successfully',
                'data'=> $data
            ]);
            
            
        
    }
    
    public function viewReviewOrder($orderId){
        $order=$this->orderRepo->viewReviewOrder($orderId,$this->reviewOrder);
                           if(is_string($order)){
            return response()->json(['status'=>false,'message'=>$order],404);
        }
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'review for this order has been getten  successfully',
                'data'=> $order
            ]);
            
    }

}
