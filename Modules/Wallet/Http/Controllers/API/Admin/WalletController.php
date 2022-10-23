<?php

namespace Modules\Wallet\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\Wallet;
use Modules\Movement\Entities\Movement;
use Modules\Coupon\Entities\Coupon;
use Modules\Wallet\Http\Requests\AddToWalletRequest;
use Modules\Wallet\Repositories\User\WalletRepository;
// use Modules\Movement\Repositories\MovementRepository;
class WalletController extends Controller
{
  /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var WalletRepository
     */
    protected $walletRepo;
    /**
     * @var Wallet
     */
    protected $wallet;
   
    /**
     * @var Movement
     */
    protected $movement;
   

    /**
     * WalletsController constructor.
     *
     * @param WalletRepository $wallets
     */
    public function __construct(BaseRepository $baseRepo, Wallet $wallet,WalletRepository $walletRepo, Movement $movement)
    {
     $this->middleware(['permission:wallets_add'])->only('addToWallet');
     $this->middleware(['permission:wallets_balance'])->only('balanceWallet');

        
        $this->baseRepo = $baseRepo;
        $this->wallet = $wallet;
        $this->movement = $movement;
        $this->walletRepo = $walletRepo;
    }

    ///for user
    public function addToWallet(AddToWalletRequest $request){
        // try{
            $movementWallet=$this->walletRepo->AddToWallet($this->wallet,$this->movement,$request);
            if(is_string($movementWallet)){
                return response()->json(['status'=>false,'message'=>$movementWallet],400);
            }
    
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movementWallet],200);
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
        
    }
    public function makeReplacingPoints($points){
        try{
            $makeReplacingPoints=$this->walletRepo->makeReplacingPoints($this->wallet,$this->movement,$points);
           if(is_string($makeReplacingPoints)){
                return response()->json(['status'=>false,'message'=>$makeReplacingPoints],400);
            }

            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$makeReplacingPoints],200);
        }catch(\Exception $ex){
                return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
        } 
    }
        public function pointsWallet(){
          try{
                $pointsWallet=$this->walletRepo->pointsWallet($this->wallet);

       
                $data=[
                    'count'=>$pointsWallet
                    ];
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);
            }catch(\Exception $ex){
                return response()->json(['status'=>false,'message'=>config('constants.error')],500);
            
            } 
    }
    public function countData(){
         try{
            $user=auth()->guard('api')->user();
            $wallet=Wallet::where(['user_id'=>$user->id])->first();
            $couponsCount=Coupon::where(['is_used'=>0])->count();
            $location = geoip(request()->ip());
            $currencyCountry=$location->currency;
            $data=[
                    'points_wallet'=>$wallet->points,
                    'amount_wallet'=>$wallet->amount,
                    'coupons_count'=>$couponsCount,
                    'currency_country'=>$currencyCountry
                    
                ];
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);
         }catch(\Exception $ex){
                return response()->json(['status'=>false,'message'=>config('constants.error')],500);
            
        } 
    }
 
    public function paymentCallbackWallet(Request $request,$amount,$userId,$paymentId){
        // try{
            $result=$this->walletRepo->paymentCallbackWallet($request);
            if($result&&isset($result->errors)){
                return response()->json(['status'=>false,'message'=>$result],400);
            }
            // $cardName=null;
            if($result->status=="CAPTURED"){
                // if(isset($paymentProcess->card)){
                //     $cardName='فيزا';
                // }else{
                //     $cardName='كي نت';
                // }

                $wallet=Wallet::where('user_id',$userId)->first();
                if(empty($wallet)){// 
                    $wallet=new $this->wallet;
                    $wallet->user_id=$userId;
                    $wallet->save();
                }        
                    //increase this value into wallet this user
                    $wallet->amount=$wallet->amount+$amount;
                    $wallet->save();
                    $movementName=null;
                    if($paymentId==3){
                        $movementName='ايداع بالمحفظة من خلال وسيلة دفع الكي نت';
                        
                    }
                    if($paymentId==4){
                        
                        $movementName='ايداع بالمحفظة من خلال وسيلة دفع الفيزا';
                    }
                    $movementWallet=new $this->movement;
                
                    $movementWallet->payment_id=$paymentId;
                    $movementWallet->remaining_wallet_points=$wallet->amount-$amount;
                    $movementWallet->wallet_id=$wallet->id; 
                    $movementWallet->status=1;
                    $movementWallet->name=$movementName;
                    $movementWallet->value=$amount;
                    $movementWallet->type=2; 
                    $movementWallet->save();  
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$result],200);
                }else{
                   
                    return response()->json(['status'=>false,'message'=>'فشلت العملية'],400);

                }

            
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
        
    }

    public function balanceWallet(){
        try{
            $wallet=$this->walletRepo->balanceWallet($this->wallet);
            $currency=$this->baseRepo->countryCurrency();
            $wallet->currency=$currency;
            
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallet],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
}
