<?php
namespace Modules\Wallet\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\Wallet\Repositories\User\WalletRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Modules\Wallet\Entities\Wallet;
use App\Models\TempDataUser;
class WalletRepository extends EloquentRepository implements WalletRepositoryInterface
{
    // public function unique_code($limit){
    //     return substr(base_convert)
    // }

    ///////for user//////
    // public function addToWallet($model1,$model2,$request){
    //     $data=$request->validated();
    //           $user=auth()->guard('api')->user();
    //           $totalAmount=0;
    //           //بحث هل موجود هادا اليوزر الو مبلغ من قبل لو اه لازم تجمع اللي جاي عاللي موجود يعني ما تكريت جديد امالو مش موجود فكريت جديد 
    //           $wallet=$model1->where(['user_id'=>$user->id])->first();
              
    //           if(empty($wallet)){//اي هادا اليوزر ما الو لسا محفظة في النظام فاول عملية ايداع بيتم كريتة محفظة لالو 
    //             $wallet=new $model1;
    //             $wallet->user_id=$user->id;
    //             // $wallet->amount=$data['amount'];
    //             $wallet->save();
    //                   $movementWallet=new $model2;

    //             $movementWallet->payment_id=$data['payment_id'];    
    //             // $movementWallet->value=$data['amount'];
    //             $movementWallet->wallet_id=$wallet->id;// من خلال ايدي المحفظة بعرف اليوزر ايدي تاع المحفظة هاي 
    //             $movementWallet->save();
    //               //المبلغ مش هلا بنحط انما بنحط من خلال لما ادخل ع وسيلة الدفع بحطه
    //           //هلا اللي افهمتع هنا مش حييجي الا انو يختار وسيلة دفع  اي شحن بالمحفظة من خلال وسيلة دفع معينة فلما يضغط عالزر ايداع حنخزن الوسيلة هاي بجدول الحركات تم الشحن من خلالها والمبلغ من خلال لما تضغط عالزر هادا 
    //           //بيوديه اصلا ع وسيلة الدفع هاي فالمبلغ تاع الحركة حنخزنه هلا ونزود هادا المبلغ عالمبلغ تاع محفظة اليوزر هادا 
    //           //لا المبلغ مش هنا المبلغ لما بالزر اللي بصفحة الوسيلة 
    //           //فبالزر تاع الوسيلة حكتب المبلغ بالحركة تاعة الوسيلة هاي لليوزر هادا وحزود المبلغ ع محفظته 
               
    //         //     if(!empty($data['payment_id'])){
    //         //     $movementWallet=new $model2;
    //         //     $movementWallet->payment_id=$data['payment_id'];    
    //         //     $movementWallet->value=$data['amount'];
    //         //     $movementWallet->wallet_id=$wallet->id;
    //         //     $movementWallet->save();
                    
    //         //     }else{
    //         //         $movementWallet=new $model2;
    //         //         $movementWallet->name=$data['name'];
    //         //         $movementWallet->value=$data['amount'];
    //         //         $movementWallet->wallet_id=$wallet->id;
    //         //         $movementWallet->save();
    //         //     }
    //           }else{
    //             //   $totalAmount=$totalAmount+$wallet->amount;
    //               $wallet->amount=$wallet->amount+$data['amount'];
    //               $wallet->save();
    //                               if(!empty($data['payment_id'])){
    //             $movementWallet=new $model2;
    //             $movementWallet->payment_id=$data['payment_id'];
    //             $movementWallet->value=$data['amount'];
    //             $movementWallet->wallet_id=$wallet->id;
    //             $movementWallet->save();     
    //             }else{
    //                 $movementWallet=new $model2;
    //                 $movementWallet->name=$data['name'];
    //                 $movementWallet->value=$data['amount'];
    //                 $movementWallet->wallet_id=$wallet->id;
    //                 $movementWallet->save();
    //             }
    //             }
    //       return $wallet;
    // }
    //هادا الراوت لما يضغط ع ايداع بالمحفظة بتااخد المبلغ والوسيلة بتخزنو بالستوريج وتستدعى ميثود البيمنت تاع الوسيلة وبترجع ريسبونس وهو من عندو 
    //حيفحفص لو رجع مع الريسبونس ايدي حيستدعي تاعة جيت بيمنت ستيتس  اللي لو رجع تمام الريسبونس فيها هو بستدعي ميثود تاعة انهاء الينت  اللي فيها بيتااخد المبلغ والوسيلة ليتخنزو بالداتابيس 
    
        public function addToWallet($model1,$model2,$request){
        $data=$request->validated();//payent_id , amount
                      $user=auth()->guard('api')->user();
              $wallet=$model1->where(['user_id'=>$user->id])->first();

        if($data['payment_id']==2){
          $resPayment=  $this->getResPaymentVisa($data['amount']);

          if($resPayment&&$resPayment['id']){
              
              if(empty($wallet)){// 
                $wallet=new $model1;
                $wallet->user_id=$user->id;
                $wallet->save();
              }

               Storage::put($user->id.'-amount',$data['amount']);
                Storage::put($user->id.'-payment_id',$data['payment_id']);
                //جدول فيه كل يوزر الو صف واحد مسموح تتخزن بينات فيه اللي هي الاعمدة حتكون حسب م بدي بالمشروع وكل م خزنت اكتر متغيرات بالستوريج لهادا اليوزر ف حكريت عمود جديد لاخزن الداتا لهادا اليوزر اعمل ابديت عليه واخزن هادا الجديد متلا المبلغ فيه في الصف تاعه
                
              $TempDataUser= TempDataUser::where(['user_id'=>$user->id])->first();
              if(empty($TempDataUser)){
                 $TempDataUser=new TempDataUser();
                $TempDataUser->amount=$data['amount'];
                  $TempDataUser->payment_id=$data['payment_id'];
                  $TempDataUser->user_id=$user->id;
                  $TempDataUser->save();
              }else{
                  $TempDataUser->amount=$data['amount'];
                  $TempDataUser->payment_id=$data['payment_id'];
                  $TempDataUser->user_id=$user->id;
                  $TempDataUser->save();
              }
              $movementWallet=$model2->where(['wallet_id'=>$wallet->id,'status'=>0,'payment_id'=>1,'type'=>2])->first();
          if(!empty($movementWallet)){
              return 'انت قمت بفعل هذه العملية من قبل والان في مرحلة الانتظار فعليك الذهاب الى صفحة وسائل الدفع لتقوم بالعملية فورا';
//المفروض ما تظهر للفرونت لانو بالاصل ع طول من اول م يضغط ايداع حتوديه عصفحة الوسيلة فمش حييجي هان الا لو كان مخلص من هناك           
              //فهنا تلقائي الصفحة حتوديه ع صفحة وسيلة الدفع اللي هو مختارها كان بالايداع عشان يعبي الداتا ويضغط عالزر بيوديه ع راوت انهاء اللبيمنت اي الستيتس 1 لهاي الحركة 
          }
           $movementWallet=new $model2;

                $movementWallet->payment_id=$data['payment_id'];
                $movementWallet->wallet_id=$wallet->id;// من خلال ايدي المحفظة بعرف اليوزر ايدي تاع المحفظة هاي 
                $movementWallet->status=0;//لسا ما انتقل للصفحة التانية تاعة البيمنت عشان تصير 1 
                $movementWallet->name='شحن من خلال الفيزا كارد';
                
                $movementWallet->value=$data['amount'];
                $movementWallet->type=2;//نوع   الحركة زي م هي في مستبدلة ولا مكتسبة هنا ايداع ولا شحن 
              
                $movementWallet->save();   
                
            //   dd($movementWallet);

            return ['movementWallet'=>$movementWallet,'resPayment'=>$resPayment]; // هنا بمسك الايدي وبيروح فيه ع راوت جيت بيمنت ستيتس اللي هو هاداك الراوت لو طلع منه نتيجة يدخله ع راوت ليمسك المبلغ خادا ويحطه بالمحفظة يعني الكلام اللي تحت الكود 
            
          }else{
              return 'حدث خطا ما , حاول مرة اخرى ';
              
          }
        }elseif($data['payment_id']==3){
        //   $resPayment=  $this->getResPaymentKnet($data['amount']);
            
        }

            
        //       $user=auth()->guard('api')->user();
        //       //بحث هل موجود هادا اليوزر الو مبلغ من قبل لو اه لازم تجمع اللي جاي عاللي موجود يعني ما تكريت جديد امالو مش موجود فكريت جديد 
        //       $wallet=$model1->where(['user_id'=>$user->id])->first();
              
        //       if(empty($wallet)){//اي هادا اليوزر ما الو لسا محفظة في النظام فاول عملية ايداع بيتم كريتة محفظة لالو 
        //         $wallet=new $model1;
        //         $wallet->user_id=$user->id;
        //         $wallet->save();
        //       }
        //      $movementWalletPending= $model2->where(['wallet_id'=>$wallet->id,'payment_id'=>$data['payment_id'],'status'=>0])->first();//$movementWalletPending
        //      //لو كان موجود خلص مش لازم احكيلو خلص عادي المهم ما اخزن تاني لو موجود فحخزن في حالة م يكون مش موجود من قبل وبالنهاية لما يرجع حيرجع الحركة سواء احفظتها ولا موجودة من قبل 
        //     //this payment for you (user) exist in prev. time 

        //               $movementWallet=new $model2;

        //         $movementWallet->payment_id=$data['payment_id'];
        //         $movementWallet->wallet_id=$wallet->id;// من خلال ايدي المحفظة بعرف اليوزر ايدي تاع المحفظة هاي 
        //         $movementWallet->status=0;
        //         $movementWallet->save();    
        //         Storage::put('payment_id',$data['payment_id']);
        //           //المبلغ مش هلا بنحط انما بنحط من خلال لما ادخل ع وسيلة الدفع بحطه
        //       //هلا اللي افهمتع هنا مش حييجي الا انو يختار وسيلة دفع  اي شحن بالمحفظة من خلال وسيلة دفع معينة فلما يضغط عالزر ايداع حنخزن الوسيلة هاي بجدول الحركات تم الشحن من خلالها والمبلغ من خلال لما تضغط عالزر هادا 
        //       //بيوديه اصلا ع وسيلة الدفع هاي فالمبلغ تاع الحركة حنخزنه هلا ونزود هادا المبلغ عالمبلغ تاع محفظة اليوزر هادا 
        //       //لا المبلغ مش هنا المبلغ لما بالزر اللي بصفحة الوسيلة 
        //       //فبالزر تاع الوسيلة حكتب المبلغ بالحركة تاعة الوسيلة هاي لليوزر هادا وحزود المبلغ ع محفظته 
        //       if(!empty($movementWalletPending)){
        //           return $movementWalletPending;
        //       }
          return $movementWallet;
    }
    
        public function getResPaymentVisa($amount){
         $url = "https://eu-test.oppwa.com/v1/checkouts";
            	$data = "entityId=8a8294174b7ecb28014b9699220015ca" .
                            "&amount=".$amount .
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
    //هادا الراوت بيتم استداعءه لو فعلا طلع ريسبونس تمام من جيت بيمنت ستيتس بيتم استدعاءه عشان ينخصم هلا المبلغ من الوسيلة وبينحط بالحفظة 
    
    public function finishingPayment($model1,$model2){//بالريكوسيت هادا جواته المبلغ اللي بدي اشحنه من الوسيلة للمحفظة وكمان بكون فيه الريكويستات الموجودة بباقي الانبوتس لهاي الوسيلة 
                                          $user=auth()->guard('api')->user();
                                               $amount= Storage::get($user->id.'-amount');
                                               $payment_id= Storage::get($user->id.'-payment_id');
        //   $data=$request->validated();
        //       $user=auth()->guard('api')->user();
        //       $wallet=$model1->where(['user_id'=>$user->id])->first();
        //       //فرضا لو اليوزر ضغط ع زر ايداع في المحفظة بعد م اختار وسيلة الدفع لكن ما كمل بينات وسيلة الدفع اللي راح عليها ولا ضغط عالزر اللي بهاي الصفحة بالتالي هي هيك هيك صارت حركة لالو ومتخزنة لكن ما الها مبلغ فهدا يعني انو في حركة صارت لكن ما كمل الدفع 
        //                     $payment_id=  Storage::get('payment_id');
        //       $movementWallet=$model2->where(['wallet_id'=>$wallet->id,'payment_id'=>$payment_id,'status'=>0])->first();//طبعا في اكتر من حركة لهاي المحفظة بالتالي لازم نحدد نحكي  وين هاي المحفظة ايدي بهادا الجدول وبنفس الوقت لويلة دفع معينة والستيتس تاعها بندينج
        //       //الستيتس بندينج لانو لسا ما كمل من خلال هاي الوسيلة فممكن يعمل اكتر من مرة هاي الحركة يضغط عالوسيلة ويضغط ع زر الايداع ويروح ع صفحة الوسيلة فاللي بتخزن بس اللي الستيتس تاعها بندينج تاع هاي الوسيلة يعني حندور ع هاي الوسيلة اللي الستيتس بندينج بس فالمفروض ما يكون الا وحدة ففوق لازم ما يتخزن 
        //       //لنفس المحفظة ونفس الوسيلة وستيتس بندينج اكتر من مرة 
               
        //         $movementWallet->value=$data['value'];
        //         $movementWallet->type=2;//نوع   الحركة زي م هي في مستبدلة ولا مكتسبة هنا ايداع ولا شحن 
        //         $movementWallet->remaining_wallet_points=$wallet->amount-$data['value'];//عشان مع كل حركة اكون عارف كم صار رصيد المحفظة 

        //         $movementWallet->status=1;
        //         $movementWallet->save();
        //         //increase this value into wallet this user
        //           $wallet->amount=$wallet->amount+$data['value'];
                   
        //           $wallet->save();    
        //           return $movementWallet;
            // 	$paymentStatusId= Storage::get('paymentStatusId');
            //     if($paymentStatusId==null){
            //         return 'لا يمكنك اكمال العملية لان عملية وسيلة الدفع الخاصة  بندينج';//cannt complete it , because your payment status pending
            //     }
              //بحث هل موجود هادا اليوزر الو مبلغ من قبل لو اه لازم تجمع اللي جاي عاللي موجود يعني ما تكريت جديد امالو مش موجود فكريت جديد 
              $wallet=$model1->where(['user_id'=>$user->id])->first();
            //   dd(Storage::get('amount'));



              if(empty($wallet)){// 
                $wallet=new $model1;
                $wallet->user_id=$user->id;
                $wallet->save();
              }
            //  $movementWalletPending= $model2->where(['wallet_id'=>$wallet->id,'payment_id'=>$payment_id,'status'=>0])->first();//$movementWalletPending
             //لو كان موجود خلص مش لازم احكيلو خلص عادي المهم ما اخزن تاني لو موجود فحخزن في حالة م يكون مش موجود من قبل وبالنهاية لما يرجع حيرجع الحركة سواء احفظتها ولا موجودة من قبل 
            //this payment for you (user) exist in prev. time 
             //البيمنت ايدي من السشن بنااخده هان فاللي اتخون بالايداع هو اللي اتخزن بالداتابيس وهو اللي حنكمل عليه هان فهان تكملة عاالايداع 
             
              $movementWallet=$model2->where(['wallet_id'=>$wallet->id,'status'=>0,'payment_id'=>$payment_id,'type'=>2])->first();
                if(empty($movementWallet)){
                    return 'يجب عليك الذهاب الى صفحة الايداع لوضع المبلغ واختيار الوسيلة بعد ذلك تاتي الى هنا لاكمال العملية ';
                }

             
                // Storage::put('payment_id',$data['payment_id']);
                  //المبلغ مش هلا بنحط انما بنحط من خلال لما ادخل ع وسيلة الدفع بحطه
               //هلا اللي افهمتع هنا مش حييجي الا انو يختار وسيلة دفع  اي شحن بالمحفظة من خلال وسيلة دفع معينة فلما يضغط عالزر ايداع حنخزن الوسيلة هاي بجدول الحركات تم الشحن من خلالها والمبلغ من خلال لما تضغط عالزر هادا 
               //بيوديه اصلا ع وسيلة الدفع هاي فالمبلغ تاع الحركة حنخزنه هلا ونزود هادا المبلغ عالمبلغ تاع محفظة اليوزر هادا 
               //لا المبلغ مش هنا المبلغ لما بالزر اللي بصفحة الوسيلة 
               //فبالزر تاع الوسيلة حكتب المبلغ بالحركة تاعة الوسيلة هاي لليوزر هادا وحزود المبلغ ع محفظته 
            //   if(!empty($movementWalletPending)){
            //       return $movementWalletPending;
            //   }
        //   return $movementWallet;
        //                   $movementWallet=$model2->where(['wallet_id'=>$wallet->id,'payment_id'=>$payment_id,'status'=>0])->first();//طبعا في اكتر من حركة لهاي المحفظة بالتالي لازم نحدد نحكي  وين هاي المحفظة ايدي بهادا الجدول وبنفس الوقت لويلة دفع معينة والستيتس تاعها بندينج
              //الستيتس بندينج لانو لسا ما كمل من خلال هاي الوسيلة فممكن يعمل اكتر من مرة هاي الحركة يضغط عالوسيلة ويضغط ع زر الايداع ويروح ع صفحة الوسيلة فاللي بتخزن بس اللي الستيتس تاعها بندينج تاع هاي الوسيلة يعني حندور ع هاي الوسيلة اللي الستيتس بندينج بس فالمفروض ما يكون الا وحدة ففوق لازم ما يتخزن 
              //لنفس المحفظة ونفس الوسيلة وستيتس بندينج اكتر من مرة 
            //   dd($wallet->amount-$amount);
                $movementWallet->remaining_wallet_points=$wallet->amount-$amount;//عشان مع كل حركة اكون عارف كم صار رصيد المحفظة 

                $movementWallet->status=1;
                $movementWallet->save();
                //increase this value into wallet this user
                  $wallet->amount=$wallet->amount+$amount;
                   
                  $wallet->save();    
                  //خلص هلا خلصنا من البمنت ايدي والمبلغ اللي بالسشن فبنمسحهم
                  
                                 Storage::put($user->id.'-amount',null);
                Storage::put($user->id.'-payment_id',null);                
//                وخلص هو هيك هيك مش حيستخدمو تاني هنا الا يروحو عالايداع قبل فبتخزن لحالو بالسشنز هاي اللي من الايداع
                  
                  
                  return $movementWallet;
        
    }
    //بعد هدول الزرين تاع الايداع والانهاء : من خلالهم تم ايداع بالمحفظة مبلغ من خلال وسيلة دفع معينة 

   
    public function balanceWallet($model){
                      $user=auth()->guard('api')->user();
                      //هو المفروض لكل يوزر صف واحد اصلا 
                      
        $wallet=$model->where(['user_id'=>$user->id])->first();
                      if(empty($wallet)){ 
                $wallet=new Wallet();
                $wallet->user_id=$user->id;
                $wallet->save();
              }
       return  $wallet;
    }
    
}
