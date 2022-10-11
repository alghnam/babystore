<?php
namespace Modules\Order\Repositories\User;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Repositories\User\OrderRepositoryInterface;
use Modules\Order\Entities\Order;
use DB;
use Illuminate\Http\Request;
use Modules\Auth\Entities\User;
use Modules\Cart\Entities\Cart;
use Modules\Service\Entities\Service;
use Modules\Coupon\Entities\Coupon;
use Modules\Order\Entities\AddressCodeNum;
use Modules\Order\Entities\Address;
use App\Notifications\OrderPendingNotification;
use Modules\BuyingSystemMount\Entities\BuyingSystemMount;
use App\Models\TempDataUser;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
    //for user


    // public function getCouponOrder($model,$orderId){
    //   $coupon =  $model->where('order_id',$orderId)->first();
    //   return $coupon;
    // }


      public function getServices(){
        $services = Service::get();
        return $services;
      }
    public function addOrder($model){//لما يضغط ع زر اضافة طلب  
      $order_num = mt_rand(100000, 999999);
     
      $user=auth()->guard('api')->user();

      // $cart=Cart::where('user_id',$user->id)->first();
      // if(empty($cart)){
      //   $newCart=new Cart();
      //   $newCart->user_id=$user->id;
      //   $newCart->save();
      //   $order=new $model;
      //   $order->order_num=$order_num;
      //   $order->user_id=$user->id;
      //   $order->cart_id=$newCart->id;
      //   $order->save();
      // }
      // $order=new $model;
      // $order->order_num=$order_num;
      // $order->user_id=$user->id;
      // $order->cart_id=$cart->id;
      // $order->save();

      $order=new $model;
      $order->order_num=$order_num;
      $order->user_id=$user->id;
      $order->status=0;//'Being processed'
      
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
    // public function addAddress($orderId,$model1,$model2,$request){//model1 : order , model2: address //بياخد ايدي الاوردر من برامز تاع راوت اضافة طلب 
    //    // dd($request->validated());
    //   //add an order for this user
    //   $orderCount = $model1->where('id',$orderId)->count();
    //   if($orderCount==0){
    //     return 404; //this order id not found
    //   }

    //   $countAddressOrder=  DB::table('address_order')->where(['order_id'=>$orderId])->count();
    //   //   dd($countAddressOrder);
    //   $data=$request->validated();
    //   $user=auth()->guard('api')->user();
    //   if(empty($user)){
    //     return 401;
    //   }
    //   $data['user_id']=$user->id;
    //   if($countAddressOrder==0){
    //     $data['default_address']=1;
    //     $address=$model2->create($data);
    //     DB::table('address_order')->insert(['order_id'=>$orderId,'address_id'=>$address->id]);
    //     return $address;
    //   }else{
    //     //   $data=$request->validated();
    //        $countDefaultAddress=0;
    //       $addressesOrder = DB::table('address_order')->where(['order_id'=>$orderId])->get();
    //       foreach($addressesOrder as $addressOrder){
    //         $address = $model2->where('id',$addressOrder->address_id)->first();
    //         // dd($address->default_address);
    //        if($address&&$address->default_address==1){
    //         $countDefaultAddress=1;
               
    //        }
              
    //       }
    //     // dd($countDefaultAddress);
    // if(!empty($data['default_address'])&&$data['default_address']==1&&$countDefaultAddress==1){//to prevent put default_address=1 if exist an address for this order : default_address=1
    //         return 400; //cannt put this address default because exist another address is default
            
    //     }else{
    //     $address=$model2->create(['country_id'=>$data['country_id'],'city_id'=>$data['city_id'],'town_id'=>$data['town_id'],'user_id'=>$data['user_id'],]);
    //     DB::table('address_order')->insert(['order_id'=>$orderId,'address_id'=>$address->id]);
    //     return $address->load('user');
            
    //     }
    //   }
    // }
    public function showDataUserAddress(){
       $user=auth()->guard('api')->user();
       return $user;
        
    }
    public function getAllAddressesUser($model){
               $user=auth()->guard('api')->user();

               $addresses= $user->addresses()->with(['country','city','town'])->get();

            return $addresses;
    }
  //   public function addAddress($model,$request){//model1 : address , model: address //بياخد ايدي الاوردر من برامز تاع راوت اضافة طلب 
  //     $data=$request->validated();
  //     $user=auth()->guard('api')->user();
  //     if(empty($user)){
  //       return 401;
  //     }
  //     $countAddressUser=  DB::table('user_address')->where(['user_id'=>$user->id])->count();//get addresses this user
  //    $data['user_id']=$user->id;
  //    if($countAddressUser==0){
  //      $data['default_address']=1;
  //      $address=$model->create($data);
  //      DB::table('user_address')->insert(['address_id'=>$address->id,'user_id'=>$user->id]);
  //      return $address;
  //    }else{
  //         $countDefaultAddress=0;
  //        $addressesUser = DB::table('user_address')->where(['user_id'=>$user->id])->get();
  //        foreach($addressesUser as $addressOrder){
  //          $address = $model->where('id',$addressOrder->address_id)->first();
  //         if($address&&$address->default_address==1){
  //          $countDefaultAddress=1;
              
  //         }
             
  //        }
  //         if(!empty($data['default_address'])&&$data['default_address']==1&&$countDefaultAddress==1){//to prevent put default_address=1 if exist an address for this order : default_address=1
  //           return 400; //cannt put this address default because exist another address is default
                  
  //         }else{
  //           $address=$model->create(['country_id'=>$data['country_id'],'city_id'=>$data['city_id'],'town_id'=>$data['town_id']]);
  //           DB::table('user_address')->insert(['user_id'=>$user->id,'address_id'=>$address->id]);

  //           return $address;
  //         }
  //    }
  //  }
  public function addAddress($model,$request){//model: address  
    $data=$request->validated();

    $user=auth()->guard('api')->user();
    //لازم احكيلو ما بزبط اتضيف عنوان دون اضافة اروردر 
    
    // $order_id= Storage::get($user->id.'-orderId');

    $addressesUser=$user->addresses;//get addresses this user
   $countAddressUser= count($addressesUser);
   if($countAddressUser==0){
     $data['default_address']=1;
     $address=$model->create($data);
     $address->users()->attach($user->id);
    //  if($order_id){
         
    //  $address->orders()->attach($order_id);
    //  }
     return $address;
   }else{

       
               //عشان لما احكيلو انت مش ماكد قمك يعرف تاع اي عنوان فلازم كل عنوان بعنوانه يعني ما يقدر يشيف عنوان الا يامد الرقم اللي للعنوان اللي حطه وما يقدر يعمل اتمام الطلب الا يكون ماكدهم اي ماكد اخر عنوان يعني 
        //لما تيجيه انت غير مءكد رقمك ل لك لا تستطيع اتمام الطلب هو لحالو حيعرف انو لاخر عنوان 
        
//لازم احط شرط هنا انو ما بزبط يضيف عنوان تاني وهو مش عامل تاكيد لرقمه اللي بالعنوان اللي قبل 
                         //بدي افحص الرقم الموجود السابق الموجود بالستوريج هل مؤكد ولا لا  عشان لو لا ما يقدر يضيف واحد تاني الا ياكد اللي قبله القديم ووهيك 
                         //بنعمل انشاء لليتوريج بالريجستر وبس لانو باللوجين بزبطش واحد يكون عامل كتير اشياء ويفتح التطبيق تاني يعمل لو جين يلاقي ولا كانو عمل اشي من قبل 
                         //فهادا اللي عامل ريجستر بييجي هان عطول لو عطول اجى هان بلاقي فاضي اي مش ماكد الرقم وهو اصلا مش ضايف اي عنوان فاحنا هنا بنتحكيلو عليك تاميد رقمك الذي بعنوانك السابق او انت لم تضيف اي عنوان من قبل  بس لا ما بزبط لانو كل م ييجي يضيف حيطلعلو هاي المسج
                         
                         //احنا هدفنا الكلي ما يقدر يضيف عنوان طالما مش ماكد اللي قبله وهكدا فبنضل كل م ييجي يضيف عنوان نشوف العنوان اللي قبله لهادا اليوزر فبهيك ما بقدر يضيف عنوان الا ماكد اللي قبله 
                         
                     $latestAddressUser=$user->addresses()->latest('id')->first();
                    //  dd($latestAddressUser->id);
                              $addressU = $model->where('id',$latestAddressUser->id)->first();
                            //   dd($addressU);
if($addressU->original_confirmed==0){
    return 'لا تستطيع ادخال عنوان اخر دون تاكيد الرقم المدخل بالعنوان السايق له ';
}
       $countDefaultAddress=0;
   foreach($addressesUser as $addressUser){
         $address = $model->where('id',$addressUser->id)->first();
        if($address&&$address->default_address==1){
         $countDefaultAddress=1;
            
        }
           
       }
        if(!empty($data['default_address'])&&$data['default_address']==1&&$countDefaultAddress==1){//to prevent put default_address=1 if exist an address for this order : default_address=1
//remove default=1 from address default to make this address a default
    //   $address= $model::where(['default_address'=>1,'user_id'=>$user->id])->first();
       $address= $model::where(['default_address'=>1])->first();

    //   $userAddress= DB::table('user_address')->where(['address_id'=>$address->id,'user_id'=>$user->id])->first();
    //   if(!empty($userAddress)){
            $address->users()->attach($user->id);

                //  dd($address);
        if($address){
            $address->default_address=0;
            // if($order_id){
            // $address->orders()->attach($order_id);
                
            // }
            $address->save();
            
        }
        //make this address default=1

          $address=$model->create($data);
     $address->users()->attach($user->id);
        //   if($order_id){
              
        //  $address->orders()->attach($order_id);
        //   }

          return $address; //added your address default
          
    //   }
    //   else{
    //       return 'لا تستطيع اضافة هذا العنوان لانك قمت بعمله ديفولت وبالطبع يوجد عنوان اخر ديفولت من عناوينك';
    //   }
                
        }else{
        //   $data['user_id']=$user->id;

          $address=$model->create($data);
          
               $address->users()->attach($user->id);

        //   if($order_id){
              
        //     $address->orders()->attach($order_id);
        //   }


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

        // return __('code is invalid, try again');
        return 'هذا الكود غير صالح حاول مرة اخرى';
            }
if ($addressCodeNum->created_at > now()->addHour()) {
    $addressCodeNum->delete();
        // return __('code is expire');
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
            $addressUser=DB::table('user_address')->where(['user_id'=>$user->id,'address_id'=>$id])->first();
            if(empty($addressUser)){
            return 'هذا العنوان ليس لك بالفعل لذلك لا يمكنك رؤيته';
                
            }

        $address=$model->where('id',$id)->first();
        if(empty($address)){
         //   return __('address not found');
            return 'هذا العنوان غير موجود';
        }   
        return $address->load(['country','city','town']);
    }
    public function updateAddress($addressId,$model,$request){
        $data=$request->validated();
            $user=auth()->guard('api')->user();
        $address=$model->where('id',$addressId)->first();
        if(empty($address)){
         //   return __('address not found');
            return 'هذا العنوان غير موجود';
        }
        
                        $addressUser=DB::table('user_address')->where(['user_id'=>$user->id,'address_id'=>$addressId])->first();
            if(empty($addressUser)){
            return 'هذا العنوان ليس لك بالفعل لذلك لا يمكنك التعديل عليه';

            }
        //dd($model);
        if(!empty($address)){
          $address->first_name=$data['first_name'];
          $address->last_name=$data['last_name'];
          $address->phone_no=$data['phone_no'];
          $address->country_id=$data['country_id'];
          $address->city_id=$data['city_id'];
          $address->town_id=$data['town_id'];
          $address->home_no=$data['home_no'];
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
                       //عشان لو عمل ابديت وجاي من الريسكوست 1 وهو في اصلا 1 بالعناوين تاعة اليوزر بالتالي برفع ال 1 من تاعون اليوزر عشان اللي جاي بس لو اللي جاي مش 1 خلص خليه على م هو عليه ادريسيس اليوزر واللي جاي هو 0 فتمام 
                       
// $address= $model::where(['default_address'=>1])->first();
//       $addressUser= DB::table('user_address')->where(['address_id'=>$address->id,'user_id'=>$user->id])->first();
//                                                      // dd($addressUser);
//                         if($addressUser){
//                             ///لو كان اعملت ابديت ع واحد من الادريسيس 
//                             if($data['default_address']==1){
                                
//                             $addressUser->default_address=0;
//                             $addressUser->save();
//                                 // $address->default_address=$data['default_address'];
//                                 $address->default_address=1;
//                                 $address->save();
//                             }else{
//                             $address->default_address=0;
//                                 $address->save();
//                             }
//                         }else{//اذا اصلا ما في لهادا اليوزر ايشي ديفولت اي ما في 1 بالتالي خلص اللي جاي جاي بس هو بالاصل ما بزبط الا يكون عالاقل واحد ديفولت منهم اصلا 
                        
//                             $address->default_address=$data['default_address'];
//                                 $address->save();
//                         }


  //check if default_address=1 from req , check if found any address for this user : default_address=1 , to make 0 and give 1 for that come from req
                        //  $addressUser= $model->where(['default_address'=>1,'user_id'=>$user->id])->first();
                        //get default_address=1 for this user
    //                     $user->addresses()->where();
    //                     $addressU= $model::where(['default_address'=>1])->first();
    //   $addressUser= DB::table('user_address')->where(['address_id'=>$addressU->id,'user_id'=>$user->id])->first();
                 $addressesUser= DB::table('user_address')->where(['user_id'=>$user->id])->get();//get all addresses for this user
                 //get default address in these addresses user to check on it : if =1 t update on it 0 
                    foreach($addressesUser as $addressUser){
                        $addressDefaultUser=$model->where(['id'=>$addressUser->address_id])->first();
                        if($addressDefaultUser->default_address==1){
                             $addressDefaultUser->default_address=0;
                                        $addressDefaultUser->save();
                        }
                    }
                       

                                $address->default_address=$data['default_address'];



                }
                      $address->save();

        //   dd($address->street_number);

          // $address=  $model->update($data);
        }
        return $address;
    }
    
    public function deleteAddress($addressId,$model){
         $user=auth()->guard('api')->user();
        $address=$model->where('id',$addressId)->first();
        if(empty($address)){
         //   return __('address not found');
            return 'هذا العنوان غير موجود';
        }
        
                        $addressUser=DB::table('user_address')->where(['user_id'=>$user->id,'address_id'=>$addressId])->first();
            if(empty($addressUser)){
            return 'هذا العنوان ليس لك بالفعل لذلك لا يمكنك التعديل عليه';

            }
        $address= $model->where(['id'=>$addressId])->first();

        if(empty($address)){
         //   return __('address not found');
            return 'هذا العنوان غير موجود';
        }
        
        //فحص هل هذا العنوان الذي لهعادا اليوزر هل ديفولت فلا يجوز حذفه 

          if($address->default_address==1){
              return 'لا يمكنك حذف هذا العنوان لانه ديفولت';
          }
          $address->delete();
            return 200;
        
    }
    public function getResPaymentVisa($totalPrice){
         $url = "https://eu-test.oppwa.com/v1/checkouts";
            	$data = "entityId=8a8294174b7ecb28014b9699220015ca" .
                            "&amount=".$totalPrice .
                            "&currency=EUR" .
                            "&paymentType=DB";
            
            	$ch = curl_init();
            	curl_setopt($ch, CURLOPT_URL, $url);
            	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                               'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
            	curl_setopt($ch, CURLOPT_POST, 1);
            	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
            	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            	$responseData = curl_exec($ch);
            	if(curl_errno($ch)) {
            		return curl_error($ch);
            	}
            	curl_close($ch);
            	$res=json_decode($responseData,true);
            	return $res;
    }
    public function reFinishingOrder($model1,$model2,$model3,$request,$orderId){//req: delivery, payment_id

    //model1: order,model2:wallet, model3 : movement 
             $data=$request->validated();
    //check if address confirmed or not
         $user=auth()->guard('api')->user();
        $phone_no_address=Storage::get($user->id.'-phone_no_address');
        if($phone_no_address){

       $address= Address::where(['phone_no'=>$phone_no_address,'confirmed'=>1])->first();
        if(!$address){
          //  return __('you cannt add your order , because you not make confirmation on your phone no.');
            return 'لا يمكنك اضافة طلبك لانك لم تعمل تاكيد لرقم موبايلك';
        }
        }else{
           // return __('you not add your address');
            return 'يجب عليك اضافة عنوان قبل انهاء طلبك';
        }
       

        //arr products from my selecting from upsells
            $totalPrice=0;
                                   $productsOrder=json_decode(explode(', ', $data['products'])[0]);

        foreach($productsOrder as $pro){
            $ProductArrayAttribute=ProductArrayAttribute::where(['id'=>$pro])->first();
            if(!empty($ProductArrayAttribute)){
            $totalPrice=$totalPrice+($ProductArrayAttribute->price_discount_ends);
            //هنا بعد م اخلص الاور=در بشوف البرودكت هادا بجدول البرودكتز وبزود العدا=د 
               $product= Product::where(['id'=>$ProductArrayAttribute->product_id])->first();
               $product->orders_counter=$product->orders_counter+1;
               $product->save();
            }
        }
             


         $order=$model1->where(['id'=>$orderId])->first();
         
        $order->products_count=count($productsOrder);
        $order->price=$order->price+$totalPrice;
       

        $order->save();
        $wallet=$model2->where(['user_id'=>$user->id])->first();
          //شراء
          if(empty($wallet)){//اي هادا اليوزر ما الو لسا محفظة في النظام فاول عملية ايداع بيتم كريتة محفظة لالو 
            $wallet=new $model2;
            $wallet->user_id=$user->id;
            $wallet->save();
          }
        //تخزين بجدول الحركات انو صارت حركة اسمها شراء منتجات والمبلغ تاع الحركة بردو بهادا   الجدول والمبلغ هادا حينقص من قيمة المحفظة تاعة اليوزر لانو تم شارء منتجات 
        //حنشى حركة جديدة وححط بالمحفظة 
            //حتحفص شو النوع اللي اختارو ليدفع من خلالو 
            if(!empty($data['payment_id'])&&$data['payment_id']==1){//اي اختار المحفظة ليشتري من خلالها 
                  //فحص هل المبلغ اللي بالمحفظة بيكفي للشراء امنا لا اي اكبر من مبلغ الشراء ولا لا 
                  if($wallet->amount<$totalPrice){
                      //return __('your amount in wallet not enough for buying operation');
                      return 'رصيدك غير كافي لاجراء عملية الشراء';

                  }
                  $wallet->amount=$wallet->amount-$totalPrice;
                       
                       $wallet->save();    
                  
                
            }
            if(!empty($data['payment_id'])&&$data['payment_id']==2){//visa
            //اللي حيتغير الانتيتي ايدي والعملة حسب م يطلب هو وما انسى احطهم بن=ملف الانف 

                $this->getResPaymentVisa($totalPrice);   
            }
            
            if(!empty($data['payment_id'])&&$data['payment_id']==3){//k net
                $knetGateway = new KnetBilling([
                                'alias'        => 'YOUR_KNET_ALIAS',
                                'resourcePath' => '/home/my_web_app_folder/' //Absolute Path to where the resource.cgn file is located
                            ]);
            
                $knetGateway->setResponseURL('http://mywebapp.com/payment/response.php');
                $knetGateway->setErrorURL('http://mywebapp.com/payment/error.php');
                $knetGateway->setAmt(100); 
                $knetGateway->setTrackId('123456'); // unique string
                
                // Refer the KnetBilling class for other configurations that can be set like currency, language etc
               $knetGateway->requestPayment();
               
               
               /** 
            * Get the URL for the Payment and assign it to a variable. this is the URL we will use to redirect the
            * customer to payment gateway 
            */

                $paymentURL = $knetGateway->getPaymentURL();

            //Note: If the Payment URL returns null, most probably you messed up with configuration, or resource path. Check the example in the below section how to ideally request for the payment, which helps to debug for the errors
            return $paymentURL;

               
                }elseif(!empty($data['payment_id'])&&$data['payment_id']==4){
                    $mountBuyingSystem=BuyingSystemMount::first();
                    if((int)$mountBuyingSystem->amount>$totalPrice){
                        return 'لا يمكنك الشراء من خلال الاستلام يدوي لان سعر فاتورتك اعلى من السعر المحدد بالنظام';
                    }
                
                }
                  $walletId=$wallet->id;
            $status=1;//هنا الستيتس بحالة غير وسيلة الدفع بتطلع ع طول الستيتس مش بندينج
            $type=3;//شراء
            $remaining_wallet_points=$wallet->amount-$totalPrice;
            
                
            if(!empty($data['payment_id'])&&$data['payment_id']==1){
                $movementWalletName='شراء من خلال المحفظة';
                $this->createMovement($model3,$totalPrice,$movementWalletName,$walletId,$status,$type,$remaining_wallet_points);
                
            }elseif(!empty($data['payment_id'])&&$data['payment_id']==2){
                $movementWalletName='شراء من خلال فيزا كارد';
                $this->createMovement($model3,$totalPrice,$movementWalletName,$walletId,$status,$type,$remaining_wallet_points);
                
            }elseif(!empty($data['payment_id'])&&$data['payment_id']==3){
               $movementWalletName='شراء من خلال كي نت';
                $this->createMovement($model3,$totalPrice,$movementWalletName,$walletId,$status,$type,$remaining_wallet_points);

            }elseif(!empty($data['payment_id'])&&$data['payment_id']==4){
               $movementWalletName='شراء من خلال الاستلام باليد';
                $this->createMovement($model3,$totalPrice,$movementWalletName,$walletId,$status,$type,$remaining_wallet_points);

            }
            $user->notify(new OrderPendingNotification($order->load(['payment','service'])));//user : will send to his this notification 

    
              return $order;

    }


    public function finishingOrder($model1,$model2,$model3,$request){//req: delivery, payment_id

    //model1: order,model2:wallet, model3 : movement 
             $data=$request->validated();
    //check if address confirmed or not
         $user=auth()->guard('api')->user();
        $phone_no_address=Storage::get($user->id.'-phone_no_address');
        if($phone_no_address){

       $address= Address::where(['phone_no'=>$phone_no_address,'confirmed'=>1])->first();
        if(!$address){
          //  return __('you cannt add your order , because you not make confirmation on your phone no.');
            return 'لا يمكنك اضافة طلبك لانك لم تعمل تاكيد لرقم موبايلك';
        }
        }else{
           // return __('you not add your address');
            return 'يجب عليك اضافة عنوان قبل انهاء طلبك';
        }
                  
        $addressesUserCount=$user->addresses()->where(['address_id'=>$data['address_id']])->count();
        if($addressesUserCount==0){
            return 'لا تستطتيع اضافة هذا العنوان على الاوردر الخاص بك لان هذا العنوان ليس خاص بك';
        }
           $cart= Cart::where(['user_id'=>$user->id])->first();
            if(!$cart){
              $cart=  new Cart();
              $cart->user_id=$user->id;
              $cart->save();
              }
             //هنا بتخزن ايدي السيرفيس لو بدنا نعرف قيمة التوصيل السيرفيس يعني من خلال الايدي اللي دخل من الريكويست وخلص
            $cart= Cart::where(['id'=>$cart->id])->first();
             $productCartCount = DB::table('product_cart')->where(['cart_id'=>$cart->id])->count();
            if(empty($cart->productArrayAttributes)){
                //return __('there is not found any product in your cart to make an order');
                return 'لا يوجد اي منتج في سلتك لعمل اوردر ';
            }
            $totalPrice=0;
            $totalPriceBill=0;
            foreach($cart->productArrayAttributes as $productArrayAttribute){
                $totalPrice=$totalPrice+($productArrayAttribute->price_discount_ends*$productArrayAttribute->pivot->quantity);
            //هنا بعد م اخلص الاور=در بشوف البرودكت هادا بجدول البرودكتز وبزود العدا=د 
               $product= Product::where(['id'=>$productArrayAttribute->product_id])->first();
               $product->orders_counter=$product->orders_counter+1;
               $product->save();
            }
                        $service=Service::where(['id'=>$data['service_id']])->first();

              if(!empty($data['coupon_id'])){
                $coupon = Coupon::where('id',$data['coupon_id'])->first();
                if($coupon->value>$totalPrice+$service->value){
                    return 'لا تستطيع اكمال هذا الطلب لان قيمة الكوبون الذي تم استخدامه اكبر من قيمة طلبك';
                }
                
                $totalPriceBill=($totalPrice+$service->value)-(int)$coupon->value;
                //   dd($totalPrice+$service->value);
            }else{
                $totalPriceBill=$totalPrice+$service->value;
             }
             
            $order=$model1->where(['id'=>$data['order_id']])->first();
            $order->user_id=$user->id;
            $order->products_count=$productCartCount;
            $order->price=$totalPriceBill;
            $order->service_id=$data['service_id'];
            $order->address_id=$data['address_id'];
            $order->payment_id=$data['payment_id'];
            $order->status=1;//'Shipping'
            $order->save();

            // dd($data['coupon_id']);
          
                $wallet=$model2->where(['user_id'=>$user->id])->first();
                  //شراء
                  if(empty($wallet)){//اي هادا اليوزر ما الو لسا محفظة في النظام فاول عملية ايداع بيتم كريتة محفظة لالو 
                    $wallet=new $model2;
                    $wallet->user_id=$user->id;
                    $wallet->save();
                  }
        //تخزين بجدول الحركات انو صارت حركة اسمها شراء منتجات والمبلغ تاع الحركة بردو بهادا   الجدول والمبلغ هادا حينقص من قيمة المحفظة تاعة اليوزر لانو تم شارء منتجات 
        //حنشى حركة جديدة وححط بالمحفظة 
            //حتحفص شو النوع اللي اختارو ليدفع من خلالو 
            if(!empty($data['payment_id'])&&$data['payment_id']==1){//اي اختار المحفظة ليشتري من خلالها 
                  //فحص هل المبلغ اللي بالمحفظة بيكفي للشراء امنا لا اي اكبر من مبلغ الشراء ولا لا 
                  if($wallet->amount<$totalPriceBill){
                      //return __('your amount in wallet not enough for buying operation');
                      return 'رصيدك غير كافي لاجراء عملية الشراء';
                      
                  }

                   //ولو في كوبون الخصم بردو ينخصم منها 
                    $wallet->amount=$wallet->amount-$totalPriceBill;
                    $wallet->save();    

                    // return $order->load('service');
                
                
            }
            if(!empty($data['payment_id'])&&$data['payment_id']==2){//visa
            //اللي حيتغير الانتيتي ايدي والعملة حسب م يطلب هو وما انسى احطهم بن=ملف الانف 

                $this->getResPaymentVisa($totalPriceBill);   
                    
                           
                
            }
            
            if(!empty($data['payment_id'])&&$data['payment_id']==3){//k net
                $knetGateway = new KnetBilling([
                'alias'        => 'YOUR_KNET_ALIAS',
                'resourcePath' => '/home/my_web_app_folder/' //Absolute Path to where the resource.cgn file is located
            ]);
            
            $knetGateway->setResponseURL('http://mywebapp.com/payment/response.php');
            $knetGateway->setErrorURL('http://mywebapp.com/payment/error.php');
            $knetGateway->setAmt(100); 
            $knetGateway->setTrackId('123456'); // unique string
            
            // Refer the KnetBilling class for other configurations that can be set like currency, language etc
               $knetGateway->requestPayment();
               
               
               /** 
                * Get the URL for the Payment and assign it to a variable. this is the URL we will use to redirect the
                * customer to payment gateway 
                */

                    $paymentURL = $knetGateway->getPaymentURL();
                    
                    //Note: If the Payment URL returns null, most probably you messed up with configuration, or resource path. Check the example in the below section how to ideally request for the payment, which helps to debug for the errors
                    return $paymentURL;
                }elseif(!empty($data['payment_id'])&&$data['payment_id']==4){
                    $mountBuyingSystem=BuyingSystemMount::first();
                    if((int)$mountBuyingSystem->amount>$totalPriceBill){
                        return 'لا يمكنك الشراء من خلال الاستلام يدوي لان سعر فاتورتك اعلى من السعر المحدد بالنظام';
                    }
                
                 
                }

              Storage::put($user->id.'-orderId',null);//خلص لانو انتهى من الاوردر هادا وعمله عشان لما ييجي بدو يعمل اوردر جديد يكون عادي 
                $TempDataUser= TempDataUser::where(['user_id'=>$user->id])->first();
              if(empty($TempDataUser)){
                 $TempDataUser=new TempDataUser();
                 $TempDataUser->user_id=$user->id;
                $TempDataUser->order_id=null;
                  $TempDataUser->save();
              }else{
                $TempDataUser->order_id=null;
                  $TempDataUser->save();
              }
                 //put coupon code if found -> used****************************
                 if(!empty($data['coupon_id'])){
                    $coupon=  Coupon::where('id',$data['coupon_id'])->first();
                    if(!empty($coupon)){
                        // dd($coupon->original_is_used);
                        if($coupon->original_is_used==1){
                        // return __('you make  coupon code  used in previous time, pls put another coupon code');
                        return 'انت تستخدم كوبون تم استخدامه من قبل , من فضلك اختار كوبونا اخر لاستخدامه';
                            
                        }
                        $coupon->is_used=1;
                        $coupon->order_id=$order->id;
                        $coupon->save();
                    }
                 }
            
            
            $walletId=$wallet->id;
            $status=1;//هنا الستيتس بحالة غير وسيلة الدفع بتطلع ع طول الستيتس مش بندينج
            $type=3;//شراء
            $remaining_wallet_points=$wallet->amount-$totalPriceBill;
            
            if(!empty($data['payment_id'])&&$data['payment_id']==1){
                $movementWalletName='شراء من خلال المحفظة';
                $this->createMovement($model3,$totalPriceBill,$movementWalletName,$walletId,$status,$type,$remaining_wallet_points);
                
            }elseif(!empty($data['payment_id'])&&$data['payment_id']==2){
                $movementWalletName='شراء من خلال فيزا كارد';
                $this->createMovement($model3,$totalPriceBill,$movementWalletName,$walletId,$status,$type,$remaining_wallet_points);
                
            }elseif(!empty($data['payment_id'])&&$data['payment_id']==3){
               $movementWalletName='شراء من خلال كي نت';
                $this->createMovement($model3,$totalPriceBill,$movementWalletName,$walletId,$status,$type,$remaining_wallet_points);

            }elseif(!empty($data['payment_id'])&&$data['payment_id']==4){
               $movementWalletName='شراء من خلال الاستلام باليد';
                $this->createMovement($model3,$totalPriceBill,$movementWalletName,$walletId,$status,$type,$remaining_wallet_points);

            }
            $user->notify(new OrderPendingNotification($order->load(['payment','service'])));//user : will send to his this notification 

              return $order;

    }
    
    public function createMovement($model3,$totalPriceBill,$movementWalletName,$walletId,$status,$type,$remaining_wallet_points){
        $movementWallet=new $model3;
        $movementWallet->value=$totalPriceBill;
        $movementWallet->name=$movementWalletName;
        $movementWallet->wallet_id=$walletId; 
        $movementWallet->status=$status;  
        $movementWallet->type=$type;
        $movementWallet->save();  
    }
    ///////////////
    public function myOrders($model){
              $user=auth()->guard('api')->user();
           $orders= $model->where(['user_id'=>$user->id])->with(['user','reviewOrder','productArrayAttributes','address.country','address.city','address.town','payment','service','products','products.productImages'])->paginate(5);
           if(count($orders)==0){
              // return __('not found any order for you(user)');
               return 'لا يوجد اي طلب لك لعرضه بقائمة طلباتك';
           }
           return $orders;
        
    }


    public function viewMyOrder($id,$model){
        $or= $model->where(['id'=>$id])->first();
        $userId=auth()->guard('api')->user()->id;
        if($or->user_id!==strval($userId)){
         //  return __('this order not for you , so you cannt see it');
           return 'هذا الطلب غير موجود من ضمن قائمة طلباتك ';
        }
           $order= $model->where(['id'=>$id])->with(['user','reviewOrder','productArrayAttributes','address.country','address.city','address.town','payment','service','products','products.productImages'])->paginate(5);
           if(count($order)==0){
              // return __('this ordernot found in system');
               return 'هذا الطلب غير موجود في النظام ';
           }else{
           return $order;
               
           }
        
    }
    public function myOrdersStatus($model,$status){
                      $user=auth()->guard('api')->user();
           $orders= $model->where(['user_id'=>$user->id,'status'=>$status])->with(['user','reviewOrder','productArrayAttributes','address.country','address.city','address.town','payment','service','products','products.productImages'])->paginate(5);
           if(count($orders)==0){
           //   return __('not found any orders with this status');
             return 'لا يوجد اي طلب من طلباتك لها هذه الحالة ';
           }
           return $orders;
    }

    // public function myOrdersBeingProcessed($model){
    //           $user=auth()->guard('api')->user();
    //       $orders= $model->where(['user_id'=>$user->id,'status'=>0])->with(['payment','service'])->paginate(5);
    //       if(count($orders)==0){
    //           return 404;
    //       }
    //       return $orders;
        
    // }
    
    //     public function myOrdersShipping($model){
    //           $user=auth()->guard('api')->user();
    //       $orders= $model->where(['user_id'=>$user->id,'status'=>1])->with(['payment','service'])->paginate(5);
    //       if(count($orders)==0){
    //           return 404;
    //       }
    //       return $orders;
        
    // }
    
    //     public function myOrdersSentDeliveredHanded($model){
    //           $user=auth()->guard('api')->user();
    //       $orders= $model->where(['user_id'=>$user->id,'status'=>2])->with(['payment','service'])->paginate(5);
    //       if(count($orders)==0){
    //           return 404;
    //       }
    //       return $orders;
        
    // }
    
    //     public function myOrdersHanging($model){
    //           $user=auth()->guard('api')->user();
    //       $orders= $model->where(['user_id'=>$user->id,'status'=>3])->with(['payment','service'])->paginate(5);
    //       if(count($orders)==0){
    //           return 404;
    //       }
    //       return $orders;
        
    // }
        public function addReviewOrder($orderId,$model,$request){
                        $data= $request->validated();
            $enteredData=  Arr::except($data ,['image']);
            $user=auth()->guard('api')->user();
          $reviewOrderUserCount= $model->where(['user_id'=>$user->id,'order_id'=>$orderId])->count();
          if($reviewOrderUserCount!==0){
              // return __('cannt add another review for this order , because you added a review on same order in prev. time');
                return 'لا يمكنك اضافة تعليق اخر على الاوردر لانك اضفت بالفعل قبل وقت عليه';
            
          }
                      $OrderUserAcceptReview= Order::where(['id'=>$orderId,'status'=>3])->first();
            if(empty($OrderUserAcceptReview)){
                // return __('cannt add your review in this order because its status : sent delivered handed');
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
            //   return __('thtere is not found review for this order');
            return 'لا يوجد ااي تعليق على هذا الطلب الى غاية الان';
           }else{
               return $reviewOrderUser;
           }
    }
  
}
