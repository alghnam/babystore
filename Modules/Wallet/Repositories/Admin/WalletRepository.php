<?php
namespace Modules\Wallet\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\Wallet\Repositories\User\WalletRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Modules\Wallet\Entities\Wallet;
use App\Models\TempDataUser;
use App\Repositories\BaseRepository;

class WalletRepository extends EloquentRepository implements WalletRepositoryInterface
{
    public function __construct(BaseRepository $baseRepo)
    {
    $this->baseRepo = $baseRepo;
    }
        public function paymentProcessWallet($amount,$userId,$paymentId){
            
           $user=auth()->guard('api')->user();
            $data['amount']= $amount;
            $data['currency']= "KWD";
            $data['customer']['first_name']= $user->first_name;
            $data['customer']['email']= $user->email;
            // ra@gmail.com
            $data['customer']['phone']['number']= $user->phone_no;
                            // $data['source']['id']= "src_kw.knet";
            if($paymentId=="3"){//payment_id:3->kent
                $data['source']['id']= "src_kw.knet";
            }elseif($paymentId=="4"){//payment_id:4->all(visa)
                $data['source']['id']= "src_all";

            }            
            $data['redirect']['url']= "https://www.baby-store.sharmhostxyz.xyz/payment-callback-wallet/".$amount.'/'.$userId.'/'.$paymentId;
            
            $headers= [
                "Content-Type:application/json",
                config('constants.payment_method_link'),


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


        public function addToWallet($model1,$model2,$request){
        $data=$request->validated();//payent_id , amount
        $user=auth()->guard('api')->user();
            $wallet=$model1->where(['user_id'=>$user->id])->first();
          $result=  $this->paymentProcessWallet($data['amount'],$user->id,$data['payment_id']);

            if($result){
                   //this result will be errors arr or url : if error -> will show err , if url , will redirect on it (to show form payment)
                   //result from payment will return into callback route to hold it , to show msg :status successfully or faild
                $url = $result;

               }else{
                    $url = 'null';

               }
               if(isset($url->errors)){
                   return $url->errors[0]->description; 
               }

                return ['url'=>$url];
    }
    
    public function paymentCallbackWallet($request){
        $input=$request->all();
                    $headers= [
                "Content-Type:application/json",
                config('constants.payment_method_link')
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
    
    public function makeReplacingPoints($model1,$model2,$points){
               $user=auth()->guard('api')->user();

       $wallet= $model1->where(['user_id'=>$user->id])->first();
        if($points>$wallet->points){
            return 'لا يوجد لديك نقاط كافية, من فضلك قم بكتابة عدد نقاط اقل';
        }
        if((int)$points !== 100 && (int)$points !== 1000){
            return 'لا يمكنك ادخال هذا العدد من النقاط , برجاء قراءة تعليمات النقاط بحرص';
        }
        // if((int)$points !== 1000){
        //  return 'لا يمكنك ادخال هذا العدد من النقاط , برجاء قراءة تعليمات النقاط بحرص';

        // }
        $name='استبدال نقاط';
       $amount=0;
        if($points==100){
            $amount=1;
        }elseif($points==1000){
            $amount=10;
        }
        if(empty($wallet)){  
                $wallet=new $model1;
                $wallet->user_id=$userId;
                $wallet->save();
              }
      
          $movementWallet=new $model2;
            $movementWallet->name=$name;
            $movementWallet->value=$points;
            $movementWallet->type=1;//Replaced
            $movementWallet->wallet_id=$wallet->id;
            $movementWallet->payment_id=null; 
            $movementWallet->status=1; 
            $movementWallet->remaining_wallet_points=$movementWallet->remaining_wallet_points-$points; 
            $movementWallet->remaining_wallet_points=$movementWallet->remaining_wallet_amounts+$amount; 
            $movementWallet->save();

            $wallet->points=$wallet->points-$points;
            $wallet->amount=$wallet->amount+$amount;
            $wallet->save();
            return $movementWallet;
         
    }
   public function pointsWallet($model){
       $user=auth()->guard('api')->user();
       $wallet=$model->where(['user_id'=>$user->id])->first();
      return  $wallet->points;
   }

    public function balanceWallet($model){
        $user=auth()->guard('api')->user();
        $wallet=$model->where(['user_id'=>$user->id])->first();
        if(empty($wallet)){ 
            $wallet=new Wallet();
            $wallet->user_id=$user->id;
            $wallet->save();
          }
         
             $location = geoip(request()->ip());
             
        if($location->currency!==config('constants.currency_system')){
            $convertingamount =  $this->baseRepo->priceCalculation($wallet->amount);
            $wallet->amount=$convertingamount;
        }
            return  $wallet;
    }
    
}
