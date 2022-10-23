<?php

namespace Modules\Movement\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Movement\Entities\Movement;
use Modules\Wallet\Entities\Wallet;
use Modules\Movement\Http\Requests\AddReplacedPointsRequest;
use App\Repositories\BaseRepository;

class MovementController extends Controller
{
   
        /**
    * MovementsController constructor.
    *
    * @param MovementRepository $movements
    */
    public function __construct(BaseRepository $baseRepo, Movement $movement)
    {
    $this->middleware(['permission:movements_get'])->only(['getAllMovementsWalletUser']);
    $this->middleware(['permission:movements_add'])->only('addReplacedPoints');
    $this->middleware(['permission:movements_remove'])->only('deleteMovement');
    
    $this->baseRepo = $baseRepo;
    $this->movement = $movement;
    }
        //for user
    public function getAllMovementsWalletUser(Request $request){
        try{
        $user=auth()->guard('api')->user();
        $wallet=Wallet::where(['user_id'=>$user->id])->first();
                      
      if(empty($wallet)){
        $wallet=new Wallet();
        $wallet->user_id=$user->id;
        $wallet->save();
      }
        $Movements=Movement::where(['wallet_id'=>$wallet->id])->where('type','!=',0)->latest()->paginate($request->total);
                      $currency=$this->baseRepo->countryCurrency();
             $location = geoip(request()->ip());
             
        if($location->currency!==config('constants.currency_system')){
            foreach($Movements as $movement){
                if($movement->remaining_wallet_points=="0"){
                    if($movement->value){
                        $convertingvalue =  $this->baseRepo->priceCalculation($movement->value);
                        $movement->value=$convertingvalue;
                    }
                    if($movement->remaining_wallet_amounts){
                        $convertingamount =  $this->baseRepo->priceCalculation($movement->remaining_wallet_amounts);
                        $movement->remaining_wallet_amounts=$convertingamount;
                    }
                    
                }
                
            }
        }
        if($wallet->amount){
            $convertingamountWallet =  $this->baseRepo->priceCalculation($wallet->amount);
            $wallet->amount=$convertingamountWallet;
        }
        $data=[
            'points_wallet'=>$wallet->amount,
            'movements'=>$Movements,
            'currency'=>$currency
            ];
                       return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);
            
                
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function getAllMovementsWalletPointsUser(Request $request){
        try{
         $user=auth()->guard('api')->user();
       $wallet= Wallet::where(['user_id'=>$user->id])->first();
       
      $movements= Movement::where(['wallet_id'=>$wallet->id,'type'=>1])->orWhere(['type'=>0])->latest()->paginate($request->total);
        $data=[
            'points_wallet'=>$wallet->points,
            'movements'=>$movements,
            ];
                       return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);
            
                
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function deleteMovement(){
        try{
            $user=auth()->guard('api')->user();
            $wallet=Wallet::where(['user_id'=>$user->id])->first();
            if(empty($wallet)){
                $wallet=new Wallet();
                $wallet->user_id=$user->id;
                $wallet->save();
              }
              $movementWallet=Movement::where(['wallet_id'=>$wallet->id,'status'=>0,'payment_id'=>1,'type'=>2])->first();
              if(!empty($movementWallet)){
                  $movementWallet->delete();
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movementWallet],200);
                
                    
              }else{
                  return response()->json(['status'=>false,'message'=>'لا يوجد حركة ايداع حالية في محفظتك لحذفها'],404);
              }
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
              
          }
    public function addReplacedPoints(AddReplacedPointsRequest $request){
        try{
            $data=$request->validated();
                   $user=auth()->guard('api')->user();
              $wallet=Wallet::where(['user_id'=>$user->id])->first();
              
              if(empty($wallet)){ 
                $wallet=new Wallet();
                $wallet->user_id=$user->id;
                $wallet->save();
              }
              if($data['amount']>$wallet->amount){

                return response()->json(['status'=>false,'message'=>'محفظتك لا تحتوي على المبلغ الكافي لاتمام عملية الاستبدال هذه '],400);

              }

                 $movement=new Movement();
                 $movement->value=$data['amount'];
                 $movement->name='replaced points';
                 $movement->type=1;//replaced
                 $movement->wallet_id=$wallet->id;
                 $movement->remaining_wallet_points=$wallet->amount-$data['amount'];//عشان مع كل حركة اكون عارف كم صار رصيد المحفظة 
                 $movement->save();
                   //خصم من المحفظو تاعتي هادا المبلغ تاع الاستبدال
                  $wallet->amount=$wallet->amount-$data['amount'];
                  $wallet->save();

            
                      return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movement->load('wallet.user')],200);

        
            }catch(\Exception $ex){
                return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
            } 
    }
}
