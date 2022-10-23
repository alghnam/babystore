<?php
namespace Modules\Order\Repositories\User;

use App\GeneralClasses\MediaClass;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Modules\Product\Entities\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Repositories\User\OrderRepositoryInterface;
use Modules\Order\Entities\Order;
use DB;
use Illuminate\Http\Request;
use Modules\Auth\Entities\User;
use Modules\Cart\Entities\Cart;
use Modules\Wallet\Entities\Wallet;
use Modules\Service\Entities\Service;
use Modules\Coupon\Entities\Coupon;
use Modules\Order\Entities\AddressCodeNum;
use Modules\Order\Entities\Address;
use Modules\Movement\Entities\Movement;
use App\Notifications\OrderPendingNotification;
use Modules\BuyingSystemMount\Entities\BuyingSystemMount;
use App\Models\TempDataUser;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Repositories\BaseRepository;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{       
    
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }


/* ------------------------ Functions --------------------------------------- */

    public function changeStatusOrder($userId,$order,$cart,$totalPriceBill,$payment_id,$service_id,$address_id){
        $productCartCount = DB::table('product_cart')->where(['cart_id'=>$cart->id])->count();
        
        $order->user_id=$userId;
        $order->products_count=$productCartCount;
        $order->price=$totalPriceBill;

        $order->address_id=$address_id;
        $order->payment_id=$payment_id;
        $order->service_id=$service_id;
        $order->status=1;//'Shipping'
        $order->save();
    }
    public function paymentProcessFinishing($totalPriceBill,$orderId,$paymentId){
        $location = geoip(request()->ip());

        if($location->currency!==config('constants.currency_system')){

             $convertingPrice =  $this->baseRepo->priceCalculation($totalPriceBill);
        }     
           $user=auth()->guard('api')->user();
            $data['amount']= $convertingPrice;
                        $data['currency']= "KWD";

            // if($paymentId==3){//payment_id:3->kent
            // $data['currency']= "KWD";
            // }elseif($paymentId==4){//payment_id:4->all(visa)
            // $data['currency']= $location->currency;

            // }
            $data['customer']['first_name']= $user->first_name;
            $data['customer']['email']= $user->email;
            // ra@gmail.com
            $data['customer']['phone']['number']= $user->phone_no;
            if($paymentId==3){//payment_id:3->kent
                $data['source']['id']= "src_kw.knet";
            }elseif($paymentId==4){//payment_id:4->all(visa)
                $data['source']['id']= "src_all";

            }
            $data['redirect']['url']= "https://www.baby-store.sharmhostxyz.xyz/payment-callback-finishing/".$orderId.'/'.$totalPriceBill.'/'.$user->id;
            
            $headers= [
                "Content-Type:application/json",
                // "Authorization:Bearer sk_test_pqcSor5QCF6k9JxhPfIeA0Ot",
                config('constants.payment_method_link'),
                // "Authorization:Bearer sk_live_q53ivUjyOs7V8WJmDPFxwatT",
                

                ];
                
            $ch=curl_init();
            $url="https://api.tap.company/v2/charges";
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $output=curl_exec($ch);
            curl_close($ch);
            $response=json_decode($output);
             if($response&&isset($response->errors)){
                 return $response;
             }
            return $response->transaction->url;
}

    public function paymentProcessReFinishing($totalPrice,$orderId,$name){
            $location = geoip(request()->ip());

            if($location->currency!==config('constants.currency_system')){
            
            $convertingPrice =  $this->baseRepo->priceCalculation($totalPrice);
            }
           $user=auth()->guard('api')->user();
            $data['amount']= $convertingPrice;
                        $data['currency']= "KWD";

            if($paymentId==3){//payment_id:3->kent
            $data['currency']= "KWD";
            }elseif($paymentId==4){//payment_id:4->all(visa)
            $data['currency']= $location->currency;

            }
            $data['customer']['first_name']= $user->first_name;
            $data['customer']['email']= $user->email;
            // ra@gmail.com
            $data['customer']['phone']['number']= $user->phone_no;
            if($paymentId==3){//payment_id:3->kent
                $data['source']['id']= "src_kw.knet";
            }elseif($paymentId==4){//payment_id:4->all(visa)
                $data['source']['id']= "src_all";

            }
            $data['redirect']['url']= "https://www.baby-store.sharmhostxyz.xyz/payment-callback-refinishing/".$orderId.'/'.$totalPrice.'/'.$user->id;
            
            $headers= [
                "Content-Type:application/json",
                config('constants.payment_method_link'),
                // "Authorization:Bearer sk_live_q53ivUjyOs7V8WJmDPFxwatT",
                

                ];
                
            $ch=curl_init();
            $url="https://api.tap.company/v2/charges";
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $output=curl_exec($ch);
            curl_close($ch);
            $response=json_decode($output);
             if($response&&isset($response->errors)){
                 return $response;
             }
            return $response->transaction->url;
}
    
    public function paymentCallback($request,$orderId){
        $input=$request->all();
                    $headers= [
                "Content-Type:application/json",
                config('constants.payment_method_link'),
                ];
                
            $ch=curl_init();
            $url="https://api.tap.company/v2/charges/".$input['tap_id'];
            // $url="https://tappayments.api-docs.io/2.0/charges/retrieve-a-charge/".$input['tap_id'];
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $output=curl_exec($ch);
            curl_close($ch);
            $response=json_decode($output);
            if($response&&isset($response->errors)){
                 return $response;
             }
            
             return $response;
             
    }

    public function checkStoreForFinishing($cart){
        if(!empty($cart)&&count($cart->productArrayAttributes)!==0){
            foreach($cart->productArrayAttributes as $productArrayAttribute){
                
                //check quntities in store
                 $proAttr= ProductArrayAttribute::where(['id'=>$productArrayAttribute->id])->first();
                if($proAttr->quantity>0){
                    if($proAttr->quantity>(int)$productArrayAttribute->pivot->quantity){
                        $pro= Product::where(['id'=>$proAttr->product_id])->first();
                        if($pro->quantity>(int)$productArrayAttribute->pivot->quantity){
                        return true;
                           
                        }else{
                            return false;
                        }
                        
                    }else{
                        return false;

                    }
                }else{
                    return false;
                 }
             }
            
        }

    }

    public function decreaseFromStoreForFinishing($order,$cart){
            if(!empty($cart)&&count($cart->productArrayAttributes)!==0){
                foreach($cart->productArrayAttributes as $productArrayAttribute){
                        //decrease  all quantities these products from store
                         $proAttr= ProductArrayAttribute::where(['id'=>$productArrayAttribute->id])->first();
                        $proAttr->quantity=$proAttr->quantity-(int)$productArrayAttribute->pivot->quantity;
                        $proAttr->save();
                        $pro= Product::where(['id'=>$proAttr->product_id])->first();
                        $pro->quantity=$pro->quantity-(int)$productArrayAttribute->pivot->quantity;
                        $pro->save();   
                        
                        $product= Product::where(['id'=>$productArrayAttribute->product_id])->first();
                        $product->orders_counter=$product->orders_counter+1;
                        $product->save();
                     //insert these products into order
                        $order->productArrayAttributes()->attach($productArrayAttribute->id,['quantity'=>$productArrayAttribute->pivot->quantity]);
                    }
                return $order->productArrayAttributes;
            }

    }
    public function processAfterPayment($userId,$wallet,$order,$cart,$totalPriceBill,$coupon_id,$payment_id,$service_id,$address_id,$status=1,$type=3,$cardName){
        // dd($cardName);
         //**decrease from store
              $this->decreaseFromStoreForFinishing($order,$cart);
            //   dd($order);
                //change status  order into shipping
                 $this->changeStatusOrder($userId,$order,$cart,$totalPriceBill,$payment_id,$service_id,$address_id);
              //delete all products from this cart
                DB::table('product_cart')->where(['cart_id'=>$cart->id])->delete();
                
                 //put coupon code if found -> used****************************
                 if(!empty($coupon_id)){
                    $coupon=  Coupon::where('id',$coupon_id)->first();
                    if(!empty($coupon)){
                        $coupon->is_used=1;
                        $coupon->order_id=$order->id;
                        $coupon->save();
                    }
                 }
            
            
            $walletId=$wallet->id;
            $status=1;//هنا الستيتس بحالة غير وسيلة الدفع بتطلع ع طول الستيتس مش بندينج
            $type=3;//شراء
            if(!empty($payment_id)&&$payment_id==1){
                $movementWalletName='شراء من خلال المحفظة';
                $this->createMovement($payment_id,$totalPriceBill,$movementWalletName,$walletId,$status,$type);
                
            }elseif(!empty($payment_id)&&$payment_id==2){
                 $movementWalletName='شراء من خلال الاستلام باليد';
                $this->createMovement($payment_id,$totalPriceBill,$movementWalletName,$walletId,$status,$type);

            }elseif(!empty($payment_id)&&$payment_id==3){
               $movementWalletName='شراء من خلال وسيلة دفع من خلال الكي نت';
                $this->createMovement($payment_id,$totalPriceBill,$movementWalletName,$walletId,$status,$type);

            }elseif(!empty($payment_id)&&$payment_id==4){
               $movementWalletName='شراء من خلال وسيلة دفع من خلال الكي نت';
                $this->createMovement($payment_id,$totalPriceBill,$movementWalletName,$walletId,$status,$type);

            }
            // $user->notify(new OrderPendingNotification($order->load(['payment','service'])));//user : will send to his this notification 
            
            return $order;
    }
    /* --------------------------------------------- --------------------------------------- */


    public function finishingOrder($model1,$model2,$model3,$request){//req: delivery, payment_id

    //model1: order,model2:wallet, model3 : movement 
    /*************senario for check and decrease**************/
    //check store, check payment method , decrease from payment method, decrease from store 
             $data=$request->validated();
             $order=$model1->where(['id'=>$data['order_id']])->first();
             if($order->status=="1"){
                 return 'هذا الطلب تم انهاؤه بالفعل , قم باضافة طلب اخر وانهاؤه هنا';
             }
               

    //check if address confirmed or not
         $user=auth()->guard('api')->user();
           $cart= Cart::where(['user_id'=>$user->id])->first();
            if(!$cart){
              $cart=  new Cart();
              $cart->user_id=$user->id;
              $cart->save();
              }
            $cart= Cart::where(['id'=>$cart->id])->first();
         //   dd($cart->productArrayAttributes);
            if(count($cart->productArrayAttributes)==0){
                //return __('there is not found any product in your cart to make an order');
                return 'لا يوجد اي منتج في سلتك لعمل اوردر ';
            }
            //**check store
            $result=$this->checkStoreForFinishing($cart);
            if($result==false){
                return 'لا يوجد كمية كافية في المخزن لجميع المنتجات التي في طلبك';
            }
            //calculate totalPrice
            $totalPrice=0;
            foreach($cart->productArrayAttributes as $productArrayAttribute){
                $totalPrice=$totalPrice+($productArrayAttribute->price_discount_ends*$productArrayAttribute->pivot->quantity);
            }
            //check price coupon
               $service=Service::where(['id'=>$data['service_id']])->first();

                    $totalPriceBill=0;
            if(!empty($data['coupon_id'])){
                $coupon = Coupon::where('id',$data['coupon_id'])->first();
                if(!empty($coupon)){
                    if($coupon->is_used==1){
                    return 'انت تستخدم كوبون تم استخدامه من قبل , من فضلك اختار كوبونا اخر لاستخدامه';
                        
                    }
                    if($coupon->value>$totalPrice+$service->value){
                        return 'لا تستطيع اكمال هذا الطلب لان قيمة الكوبون الذي تم استخدامه اكبر من قيمة طلبك';
                    }
                    $totalPriceBill=($totalPrice+$service->value)-(int)$coupon->value;
                }else{
                    $totalPriceBill=$totalPrice+$service->value;
                }
            }else{
                                    $totalPriceBill=$totalPrice+$service->value;

            }
          //  dd($totalPriceBill);
            //**check payment method 
            $wallet=$model2->where(['user_id'=>$user->id])->first();
              //شراء
              if(empty($wallet)){//اي هادا اليوزر ما الو لسا محفظة في النظام فاول عملية ايداع بيتم كريتة محفظة لالو 
                $wallet=new $model2;
                $wallet->user_id=$user->id;
                $wallet->save();
              }
              if(!empty($data['payment_id'])&&$data['payment_id']==1){//اي اختار المحفظة ليشتري من خلالها 
              if($wallet->amount<$totalPriceBill){
                  return 'رصيدك غير كافي لاجراء عملية الشراء';
                  
              }
              
              //**decrease from payment method
                $wallet->amount=$wallet->amount-$totalPriceBill;
                $wallet->save();
                            // $remaining_wallet_points=$wallet->amount;
                if(!empty($data['coupon_id'])){
                    return $this->processAfterPayment($user->id,$wallet,$order,$cart,$totalPriceBill,$data['coupon_id'],$data['payment_id'],$data['service_id'],$data['address_id'],$status=1,$type=3,$cardName=null);
                }else{
                    return $this->processAfterPayment($user->id,$wallet,$order,$cart,$totalPriceBill,$data['coupon_id']=null,$data['payment_id'],$data['service_id'],$data['address_id'],$status=1,$type=3,$cardName=null);
                }

                
            }elseif(!empty($data['payment_id'])&&$data['payment_id']==2){//upon
                $mountBuyingSystem=BuyingSystemMount::first();
                if((int)$mountBuyingSystem->mount<$totalPriceBill){
                    return 'لا يمكنك الشراء من خلال الاستلام يدوي لان سعر فاتورتك اعلى من السعر المحدد بالنظام';
                }
                //**for payment method
                //$mountBuyingSystem
                $mountBuyingSystem->mount=$mountBuyingSystem->mount+$totalPriceBill;
                $mountBuyingSystem->save();
                if(!empty($data['coupon_id'])){
                    
                    return  $this->processAfterPayment($user->id,$wallet,$order,$cart,$totalPriceBill,$data['coupon_id'],$data['payment_id'],$data['service_id'],$data['address_id'],$status=1,$type=3,$cardName=null);
                }else{
                    
                    return  $this->processAfterPayment($user->id,$wallet,$order,$cart,$totalPriceBill,$data['coupon_id']=null,$data['payment_id'],$data['service_id'],$data['address_id'],$status=1,$type=3,$cardName=null);
                }

                 
            }elseif((!empty($data['payment_id'])&&$data['payment_id']==3)||(!empty($data['payment_id'])&&$data['payment_id']==4)){//payment method 3: knet, 4: visa
            //check amount in payment method 
            // $this->checkAndDecreaseFromStore($order,$data['product_array_attributes']);
                if(!empty($data['coupon_id'])){
                 Storage::put($user->id.'-coupon_id',$data['coupon_id']);
                    
                }else{
                  Storage::put($user->id.'-coupon_id',null);

                }
                 Storage::put($user->id.'-service_id',$data['service_id']);
                 Storage::put($user->id.'-address_id',$data['address_id']);
                 Storage::put($user->id.'-payment_id',$data['payment_id']);
                
                $resultPaymentProcess=  $this->paymentProcessFinishing($totalPriceBill,$data['order_id'],$data['payment_id']);
               if($resultPaymentProcess){
                   //this result will be errors arr or url : if error -> will show err , if url , will redirect on it (to show form payment)
                   //result from payment will return into callback route to hold it , to show msg :status successfully or faild
                $url = $resultPaymentProcess;

               }else{
                    $url = 'null';

               }
               if(isset($url->errors)){
                   return $url->errors[0]->description; 
               }

                return ['url'=>$url];
            }
  


            return $order;

    }
    
      public function getServices(){
        $services = Service::get();
        return $services;
      }
    public function addOrder($model){//لما يضغط ع زر اضافة طلب  
        $order_num = mt_rand(100000, 999999);
        
        $user=auth()->guard('api')->user();
    
        $order= Order::where(['user_id'=>$user->id])->latest()->first();
            if($order&&$order->status=="0"){
                 Storage::put($user->id.'-orderId',$order->id);
                $TempDataUser= TempDataUser::where(['user_id'=>$user->id])->first();
                  if(empty($TempDataUser)){
                     $TempDataUser=new TempDataUser();
                     $TempDataUser->user_id=$user->id;
                    $TempDataUser->order_id=$order->id;
                      $TempDataUser->save();
                  }else{
                    $TempDataUser->order_id=$order->id;
    
                      $TempDataUser->save();
                  }
                 return $order;
            }else{
                
              $order=new $model;
              $order->order_num=$order_num;
              $order->user_id=$user->id;
              $order->status=0;//'hanging'
              
              $order->save();
               Storage::put($user->id.'-orderId',$order->id);
            $TempDataUser= TempDataUser::where(['user_id'=>$user->id])->first();
              if(empty($TempDataUser)){
                 $TempDataUser=new TempDataUser();
                 $TempDataUser->user_id=$user->id;
                $TempDataUser->order_id=$order->id;
                  $TempDataUser->save();
              }else{
                $TempDataUser->order_id=$order->id;

                  $TempDataUser->save();
              }
                return $order;

            }
           

    }
    public function showDataUserAddress(){
       $user=auth()->guard('api')->user();
       return $user;
        
    }
    public function getAllAddressesUser($model){
               $user=auth()->guard('api')->user();

               $addresses= $user->addresses()->with(['country','city','town'])->get();

            return $addresses;
    }
  public function addAddress($model,$request){//model: address  
    $data=$request->validated();
    $user=auth()->guard('api')->user();


    $addressesUser=$user->addresses;//get addresses this user
   $countAddressUser= count($addressesUser);
   if($countAddressUser==0){
     $data['default_address']=1;
                 $data['user_id']=$user->id;
        $data['confirmed']=0;
     $address=$model->create($data);

     return $address;
   }else{
        $latestAddressUser=$user->addresses()->latest('id')->first();
        // dd($latestAddressUser->confirmed);
        // $addressU = $model->where('id',$latestAddressUser->id)->first();
        if($latestAddressUser->confirmed==0){
            return 'لا تستطيع ادخال عنوان اخر دون تاكيد الرقم المدخل بالعنوان السايق له ';
        }
       $countDefaultAddress=0;
        foreach($addressesUser as $addressUser){
             $address = $model->where('id',$addressUser->id)->first();
            if($address&&$address->default_address==1){
             $countDefaultAddress=1;
                
            }
           
       }
        if(!empty($data['default_address'])&&$data['default_address']=="1"&&$countDefaultAddress==1){//to prevent put default_address=1 if exist an address for this order : default_address=1
            //remove default=1 from address default to make this address a default

            $address= $model::where(['default_address'=>1,'user_id'=>$user->id])->first();

                if($address){
                    $address->default_address=0;
                    $address->save();
                    
                }
                //make this address default=1
                $data['user_id']=$user->id;
        $data['confirmed']=0;

                $address=$model->create($data);

                return $address; //added your address default
                
        }else{
            $data['user_id']=$user->id;
                 $data['confirmed']=0;

            $address=$model->create($data);
           
          return $address;
        }
   }
 }
   
   public function checkCode($request,$model){
        $user=auth()->guard('api')->user();

        $phone_no_address=Storage::get($user->id.'-phone_no_address');
        $address_id=Storage::get($user->id.'-address_id');
    // find the code'
    $addressCodeNum = $model->firstWhere(['code'=> $request->code,'phone_no'=>$phone_no_address,'address_id'=>$address_id]);
    
    // check if it does not expired: the time is one hour
    if(!$addressCodeNum){
    
            return 'هذا الكود غير صالح حاول مرة اخرى';
                }
    if ($addressCodeNum->created_at > now()->addHour()) {
        $addressCodeNum->delete();
            return 'انتهت صلاحية هذا الكود';
                }
       $address= Address::where('id',$address_id)->first();
        if($address){
            $address->confirmed=1;
            $address->save();
        }
        return $addressCodeNum;


}
    public function showAddress($id,$model){
        $user=auth()->guard('api')->user();
        // =DB::table('user_address')->where(['user_id'=>$user->id,'address_id'=>$id])->first();
     $addressUser= $user->addresses();
     //dd($addressUser);
        if(empty($addressUser)){
            return 'هذا العنوان ليس لك بالفعل لذلك لا يمكنك رؤيته';
            
        }

        $address=$model->where('id',$id)->first();
        if(empty($address)){
            return 'هذا العنوان غير موجود';
        }   
        return $address->load(['country','city','town']);
    }
    public function updateAddress($addressId,$model,$request){
        $data=$request->validated();
        $user=auth()->guard('api')->user();
        $address=$model->where('id',$addressId)->first();
        if(empty($address)){
            return 'هذا العنوان غير موجود';
        }
        
            if(empty($address)){
                if($address->user_id!==$user->id){
            return 'هذا العنوان ليس لك بالفعل لذلك لا يمكنك التعديل عليه';
                }

            }
        if(!empty($address)){
          $address->first_name=$data['first_name'];
          $address->last_name=$data['last_name'];
          $address->country_id=$data['country_id'];
          $address->city_id=$data['city_id'];
          $address->town_id=$data['town_id'];
          $address->home_no=$data['home_no'];
           if(!empty($data['phone_no'])){
                if($data['phone_no'] !== $address->phone_no){
                    $address->confirmed=0;
                }
                                    $address->phone_no=$data['phone_no'];

                
            }
          if(!empty($data['piece_number'])){

            $address->piece_number=$data['piece_number'];
          }
          if(!empty($data['street_number'])){
          $address->street_number=$data['street_number'];
          }
          if(!empty($data['jada_number'])){
          $address->jada_number=$data['jada_number'];
          }
          if(!empty($data['floor_number'])){
          $address->floor_number=$data['floor_number'];
          }
          if(!empty($data['apartment_number'])){
            $address->apartment_number=$data['apartment_number'];
            }
            if(!empty($data['additional_tips'])){
              $address->additional_tips=$data['additional_tips'];
              }
              if(!empty($data['default_address'])){
                  
                //check if default_address=1 from req , check if found any address for this user : default_address=1 , to make 0 and give 1 for that come from req
                    $addressesUser= $user->addresses;//get all addresses for this user
                 //get default address in these addresses user to check on it : if =1 t update on it 0 
                    foreach($addressesUser as $addressUser){
                        $addressDefaultUser=Address::find($addressUser->id);
                        if(!empty($addressDefaultUser)){
                            if($addressDefaultUser->default_address==1){
                                 $addressDefaultUser->default_address=0;
                                            $addressDefaultUser->save();
                            }
                        }
                    }
                      $address->default_address=$data['default_address'];



                }
                $address->save();
               

        }
        return $address;
    }
    
    public function deleteAddress($addressId,$model){
         $user=auth()->guard('api')->user();
        $address=$model->where('id',$addressId)->first();
        if(empty($address)){
            return 'هذا العنوان غير موجود';
        }

            if(empty($address)){
                if($address->user_id!==$user->id){
                    return 'هذا العنوان ليس لك بالفعل لذلك لا يمكنك حذفه';
                }

            }

        

          if($address->default_address==1){
              return 'لا يمكنك حذف هذا العنوان لانه ديفولت';
          }
          $address->delete();
            return 200;
        
    }


    public function checkStoreForRefinishing($order,$product_array_attributes){
        $productsOrder=json_decode(explode(', ', $product_array_attributes)[0]);
        foreach($productsOrder as $pro){
            $ProductArrayAttribute=ProductArrayAttribute::where(['id'=>$pro])->first();
            if(!empty($ProductArrayAttribute)){
                // $totalPrice=$totalPrice+($ProductArrayAttribute->price_discount_ends);
                //check quantities in store
                $proAttr= ProductArrayAttribute::where(['id'=>$ProductArrayAttribute->id])->first();
                if($proAttr->quantity==0){
                    return false;//not found enough quantity
                }else{
                    return true;
                    
                }

            }

        }
    }
    public function decreaseFromStoreForRefinishing($order,$product_array_attributes,$totalPrice){
                    $productsOrder=json_decode(explode(', ', $product_array_attributes)[0]);
        foreach($productsOrder as $pro){
            $ProductArrayAttribute=ProductArrayAttribute::where(['id'=>$pro])->first();
            if(!empty($ProductArrayAttribute)){
                //decrease  all quantities these products from store
                $proAttr= ProductArrayAttribute::where(['id'=>$ProductArrayAttribute->id])->first();
                    $proAttr->quantity=$proAttr->quantity- 1;
                    $proAttr->save();
                
                    $pro= Product::where(['id'=>$proAttr->product_id])->first();
                    $pro->quantity=$pro->quantity- 1;
                    $pro->save();   
                
                //insert these products into this order
                $order->productArrayAttributes()->attach($ProductArrayAttribute->id,['quantity'=>1]);
               
            }

        }
             


        $order->products_count=$order->products_count+(count($productsOrder));
        $order->price=$order->price+$totalPrice;
       

        $order->save();
    }

    public function processAfterPaymentForRefinishing($wallet,$order,$totalPrice,$payment_id,$product_array_attributes,$model3,$status=1,$type=3,$cardName=null){
         //**decrease from store
              $this->decreaseFromStoreForRefinishing($order,$product_array_attributes,$totalPrice);
              
                  $walletId=$wallet->id;
            $status=1;//هنا الستيتس بحالة غير وسيلة الدفع بتطلع ع طول الستيتس مش بندينج
            $type=3;//شراء
            // $remaining_wallet_points=$wallet->amount-$totalPrice;
            if(!empty($payment_id)&&$payment_id==1){
                $movementWalletName='شراء من خلال المحفظة';
                $this->createMovement($payment_id,$totalPrice,$movementWalletName,$walletId,$status,$type);
                
            }elseif(!empty($payment_id)&&$payment_id==2){
                               $movementWalletName='شراء من خلال الاستلام باليد';
                $this->createMovement($payment_id,$totalPrice,$movementWalletName,$walletId,$status,$type);

                
            }elseif(!empty($payment_id)&&$payment_id==3){
               $movementWalletName='شراء من خلال وسيلة دفع';
                $this->createMovement($payment_id,$totalPrice,$movementWalletName,$walletId,$status,$type,$cardName);

            }
            //$user->notify(new OrderPendingNotification($order->load(['payment','service'])));//user : will send to his this notification 
            

              return $order;
    }
    public function reFinishingOrder($model1,$model2,$model3,$request,$orderId){//req: delivery, payment_id

    //model1: order,model2:wallet, model3 : movement 
    /*************senario for check and decrease**************/
    //check store, check payment method , decrease from payment method, decrease from store 
    $data=$request->validated();
    //check if address confirmed or not
    $user=auth()->guard('api')->user();
        $phone_no_address=Storage::get($user->id.'-phone_no_address');
        if(!$phone_no_address){

            return 'يجب عليك اضافة عنوان قبل انهاء طلبك';
    
        }
       

        //arr products from my selecting from upsells
        $order=$model1->where(['id'=>$orderId])->first();

        //**check store
            $result=$this->checkStoreForFinishing($order,$data['product_array_attributes']);
            if(!$result){
                return 'لا يوجد كمية كافية في المخزن لجميع المنتجات التي تطلبها';
            }
            //calculate totalPrice
            $totalPrice=0;
            if(count($order->productArrayAttributes)!==0){
                foreach($order->productArrayAttributes as $productArrayAttribute){
                    $totalPrice=$totalPrice+($productArrayAttribute->price_discount_ends*$productArrayAttribute->pivot->quantity);
                }
                
            }
        
            //**check payment method 
        $wallet=$model2->where(['user_id'=>$user->id])->first();
          //شراء
          if(empty($wallet)){
              $wallet=new $model2;
            $wallet->user_id=$user->id;
            $wallet->save();
          }
        if(!empty($data['payment_id'])&&$data['payment_id']==1){//اي اختار المحفظة ليشتري من خلالها 
            if($wallet->amount<$totalPrice){
                return 'رصيدك غير كافي لاجراء عملية الشراء';
            
            }
              //**decrease from payment method
            $wallet->amount=$wallet->amount-$totalPrice;
            
            $wallet->save();
            return $this->processAfterPaymentForRefinishing($wallet,$order,$totalPrice,$data['payment_id'],$data['product_array_attributes'],$status=1,$type=3,$cardName=null);


                
            }elseif(!empty($data['payment_id'])&&$data['payment_id']==2){
                $mountBuyingSystem=BuyingSystemMount::first();
              //**for payment method
                //$mountBuyingSystem
                $mountBuyingSystem->mount=$mountBuyingSystem->mount+$totalPrice;
                $mountBuyingSystem->save();
            return $this->processAfterPaymentForRefinishing($wallet,$order,$totalPrice,$data['payment_id'],$data['product_array_attributes'],$status=1,$type=3,$cardName=null);


            }elseif((!empty($data['payment_id'])&&$data['payment_id']==3)||(!empty($data['payment_id'])&&$data['payment_id']==4)){//payment method : knet:3 , visa:4
            //check amount in payment method 
            // $this->checkAndDecreaseFromStore($order,$data['product_array_attributes']);
              //**for payment method

                Storage::put('token',$request->token);
                Storage::put($user->id.'-payment_id-re',$data['payment_id']);
                Storage::put($user->id.'-product_array_attributes-re',$data['product_array_attributes']);
                $product_array_attributes= Storage::get($user->id.'-product_array_attributes-re');
                $resultPaymentProcess=  $this->paymentProcessRefinishing($totalPrice,$orderId,$data['payment_id']);
               if($resultPaymentProcess){
                    $url = $resultPaymentProcess;
               }else{
                    $url = 'null';

               }

                return ['url'=>$url];

             
            }

           

    }
    public function cancelOrder($id,$model){
         $order=$model->where(['id'=>$id])->first();
         if(empty($order)){
             return 'غير موجود بالنظام';
         }
         if($order->status=="-1"){
                          return 'هذا الطلب تم الغاؤه بالفعل';

         }else{
            if($order->status=="1"){
                //increas  all quantities these products into store
                foreach($order->productArrayAttributes as $productArrayAttribute){
                    $proAttr= ProductArrayAttribute::where(['id'=>$productArrayAttribute->id])->first();
                    $proAttr->quantity=$proAttr->quantity+(int)$productArrayAttribute->pivot->quantity;
                    $proAttr->save();
                
                    $pro= Product::where(['id'=>$proAttr->product_id])->first();
                    $pro->quantity=$pro->quantity+(int)$productArrayAttribute->pivot->quantity;
                    $pro->save();
                    
                } 
                $order->update(['status'=>-1]);

            }else{
                return 'لا يمكنك الغاء هذا الطلب ';
            }

         }
    }
    public function createMovement($payment_id,$totalPriceBill,$movementWalletName,$walletId,$status,$type){
        $wallet=Wallet::where(['id'=>$walletId])->first();
        $movementWallet=new Movement;
        $movementWallet->remaining_wallet_amounts=$wallet->amount-$totalPriceBill;
        $movementWallet->value=$totalPriceBill;
        $movementWallet->name=$movementWalletName;
        $movementWallet->wallet_id=$walletId; 
        $movementWallet->payment_id=$payment_id; 
        $movementWallet->status=$status;  
        $movementWallet->type=$type;
        $movementWallet->save();  
    }
    ///////////////
    public function myOrders($model,$request){
              $user=auth()->guard('api')->user();
           $myOrders= $model->where(['user_id'=>$user->id])->with(['user','reviewOrder','productArrayAttributes','productArrayAttributes.product','productArrayAttributes.image','address.country','address.city','address.town','payment','service','coupon'])->latest()->paginate($request->total);
           if(count($myOrders)==0){
               return 'لا يوجد اي طلب لك لعرضه بقائمة طلباتك';
           }
            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                foreach($myOrders as $myOrder){
                //convert this price that in dinar into currency user
                $myOrder->currency_country=$this->baseRepo->countryCurrency();

                if($myOrder->price){
                    $convertingPrice =  $this->baseRepo->priceCalculation($myOrder->price);
                    $myOrder->price=$convertingPrice;
                }
               
                if($myOrder->productArrayAttributes){
                    foreach($myOrder->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        $attr->currency_country=$this->baseRepo->countryCurrency();
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                    if($myOrder->service){
                        $convertingValueService =  $this->baseRepo->priceCalculation($myOrder->service->value);
                        $myOrder->service->value=$convertingValueService;
                    }
                     if($myOrder->coupon){
                  
                        $convertingValueCoupon =  $this->baseRepo->priceCalculation($myOrder->coupon->value);
                        $myOrder->coupon->value=$convertingValueCoupon;
                    
                     }
                }
            }
            }
         
           return $myOrders;
        
    }


    public function viewMyOrder($id,$model){
        $or= $model->where(['id'=>$id])->first();
        if(empty($or)){
            return 'غير موجود بالنظام';
        }
        $userId=auth()->guard('api')->user()->id;
        if($or->user_id!==strval($userId)){
           return 'هذا الطلب غير موجود من ضمن قائمة طلباتك ';
        }
           $order= $model->where(['id'=>$id])->with(['user','reviewOrder','productArrayAttributes','address.country','address.city','address.town','payment','service','productArrayAttributes','productArrayAttributes.image','coupon'])->first();
           if(empty($order)){
               return 'هذا الطلب غير موجود في النظام ';
           }else{
                $location = geoip(request()->ip());
                if($location->currency!==config('constants.currency_system')){
                    if($order->productArrayAttributes){
                        
                      foreach($order->productArrayAttributes as $proAttr){
                        //convert this price that in dinar into currency user
                        $proAttr->currency_country=$this->baseRepo->countryCurrency;
                        if($proAttr->original_price){
                            $convertingOriginalPriceAttr =  $this->baseRepo->priceCalculation($proAttr->original_price);
                            $proAttr->original_price=$convertingOriginalPriceAttr;
                        }
                        if($proAttr->price_discount_ends){
                            $convertingPriceEndsAttr =  $this->baseRepo->priceCalculation($proAttr->price_discount_ends);
                            $proAttr->price_discount_ends=$convertingPriceEndsAttr;
                        }
                      }
                       }
               
                }
               
           return $order;
          }
        
    }
    public function myOrdersStatus($model,$status,$request){
        $user=auth()->guard('api')->user();
       $myOrders= $model->where(['user_id'=>$user->id,'status'=>$status])->with(['user','reviewOrder','productArrayAttributes','productArrayAttributes.product','address.country','address.city','address.town','payment','service','productArrayAttributes','productArrayAttributes.image'])->latest()->paginate($request->total);
       if(count($myOrders)==0){
         return 'لا يوجد اي طلب من طلباتك لها هذه الحالة ';
       }
                   $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                foreach($myOrders as $myOrder){
                 //convert this price that in dinar into currency user
                    $myOrder->currency_country=$this->baseRepo->countryCurrency();
                    if($myOrder->price){
                        $convertingPrice =  $this->baseRepo->priceCalculation($myOrder->price);
                        $myOrder->price=$convertingPrice;
                    }
                    if($myOrder->productArrayAttributes){
                        foreach($myOrder->productArrayAttributes as $attr){
                            if($attr->original_price){
                                $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                $attr->original_price=$convertingOriginalPrice;
                                
                            }
                            if($attr->price_discount_ends){
                                $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                $attr->price_discount_ends=$convertingOriginalPrice;
                                
                            }
                    }
                        if($myOrder->service){
                            $convertingValueService =  $this->baseRepo->priceCalculation($myOrder->service->value);
                            $myOrder->service->value=$convertingValueService;
                        }
                        if($myOrder->coupon){
                            $convertingValueCoupon =  $this->baseRepo->priceCalculation($myOrder->coupon->value);
                            $myOrder->coupon->value=$convertingValueCoupon;
                        
                        }
                    }
            }
            }
         
       return $myOrders;
    }
    public function addReviewOrder($orderId,$model,$request){
        $data= $request->validated();
        $enteredData=  Arr::except($data ,['image']);
        $user=auth()->guard('api')->user();
          $reviewOrderUserCount= $model->where(['user_id'=>$user->id,'order_id'=>$orderId])->count();
          if($reviewOrderUserCount!==0){
                return 'لا يمكنك اضافة تعليق اخر على الاوردر لانك اضفت بالفعل قبل وقت عليه';
            
          }
        $OrderUserAcceptReview= Order::where(['id'=>$orderId,'status'=>3])->first();
        if(empty($OrderUserAcceptReview)){
            return 'لا يمكنك اضافة تعليف على هذا الاوردر لانه تم تسليمه';
            }
        $reviewOrder = new $model;
        $reviewOrder->user_id=$user->id;
        $reviewOrder->order_id=$orderId;
        $reviewOrder->description=$enteredData['description'];
        $reviewOrder->rating=$enteredData['rating'];
        $reviewOrder->save();
    
         if(!empty($data['image'])){
                if($request->hasFile('image')){
                    $file_path_original= MediaClass::store($request->file('image'),'review-order-images');//store Product image
                                                        $file_path_original_image_review= str_replace("public/","",$file_path_original);
                $data['image']=$file_path_original_image_review;

                }else{
                    $data['image']=$reviewOrder->image;
                }
             if($reviewOrder->image){
                
                 $reviewOrder->image()->update(['url'=>$data['image'],'imageable_id'=>$reviewOrder->id,'imageable_type'=>'Modules\Order\Entities\ReviewOrder']);
       
             }else{
       
                 $reviewOrder->image()->create(['url'=>$data['image'],'imageable_id'=>$reviewOrder->id,'imageable_type'=>'Modules\Order\Entities\ReviewOrder']);
             }
         }
         return $reviewOrder;
                 
    }
    
    public function viewReviewOrder($orderId,$model){
        $user=auth()->guard('api')->user();
       $reviewOrderUser= $model->where(['user_id'=>$user->id,'order_id'=>$orderId])->first();
       if(empty($reviewOrderUser)){
        return 'لا يوجد ااي تعليق على هذا الطلب الى غاية الان';
       }else{
           return $reviewOrderUser;
       }
    }
  
}
