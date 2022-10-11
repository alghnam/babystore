<?php

namespace Modules\Movement\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Movement\Entities\Movement;
use Modules\Wallet\Entities\Wallet;
use Modules\Movement\Http\Requests\AddReplacedPointsRequest;
class MovementController extends Controller
{
   
    
        //for user
    public function getAllMovementsWalletUser(Request $request){
                      $user=auth()->guard('api')->user();
        $wallet=Wallet::where(['user_id'=>$user->id])->first();
                      
              if(empty($wallet)){//اي هادا اليوزر ما الو لسا محفظة في النظام فاول عملية ايداع بيتم كريتة محفظة لالو 
                $wallet=new Wallet();
                $wallet->user_id=$user->id;
                $wallet->save();
              }
        $Movements=Movement::where(['wallet_id'=>$wallet->id])->paginate($request->total);
        
        $data=[
            'pionts_wallet'=>$wallet->amount,
            // 'movements'=>$Movements->load(['payment','wallet.user']),
            'movements'=>$Movements,
            ];
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'all movements wallet user has been getten successfully',
                'data'=>  $data
            ]);
    }
    public function deleteMovement(){
                              $user=auth()->guard('api')->user();
        $wallet=Wallet::where(['user_id'=>$user->id])->first();
              if(empty($wallet)){//اي هادا اليوزر ما الو لسا محفظة في النظام فاول عملية ايداع بيتم كريتة محفظة لالو 
                $wallet=new Wallet();
                $wallet->user_id=$user->id;
                $wallet->save();
              }
              $movementWallet=Movement::where(['wallet_id'=>$wallet->id,'status'=>0,'payment_id'=>1,'type'=>2])->first();
          if(!empty($movementWallet)){
              $movementWallet->delete();
                            return response()->json(['status'=>true,'message'=>'تم حذف حركة الايداع الحالية الخاصة بك بنجاح'],200);

          }else{
              return response()->json(['status'=>false,'message'=>'لا يوجد حركة ايداع حالية في محفظتك لحذفها'],404);
          }
              
          }
    public function addReplacedPoints(AddReplacedPointsRequest $request){
        //المستبدلة يعني استبدلت نقاط انخصمو من المحفظة تاعتي وبدالهم اخدت عارض الواقع اخدت مصاري متلا 
//اي لازم المبلغ اللي حكتبو اللي حستبدله يكون اقل او يساوي الموجود بالمحفظة فلو اكبر ما بزبط لانو بالفعل ما في بمحفظتي هادا المبغ لاستبدلو

     $data=$request->validated();
                   $user=auth()->guard('api')->user();
              $wallet=Wallet::where(['user_id'=>$user->id])->first();
              
              if(empty($wallet)){ 
                $wallet=new Wallet();
                $wallet->user_id=$user->id;
                $wallet->save();
              }
              if($data['amount']>$wallet->amount){
     //هل المبلغ اللي بالمحفظة 0 لو 0 اعمل حدف دعري غير م انك حتطلع رسالة 
           //عشان ما يصير بالسالب
           //لا خلص هينا وقفناه بالهندلة اي مش حيروح عالمكان اللي بيعمل تنقيص اي ما بيصير بالسالب
                // if($wallet->amount==0){
                   
                // }
                            // return response()->json(['status'=>false,'message'=>'amount in your wallet not enough this amount to replace it'],400);
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
     return response()->json([
                'status'=>true,
                'message' => 'added replaced points into your wallet ',
                'data'=>  $movement->load('wallet.user')
            ]);
    }
}
