<?php

namespace Modules\Payment\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Payment;
class PaymentController extends Controller
{
       
        /**
    * PaymentsController constructor.
    *
    * @param MovementRepository $payments
    */
    public function __construct( Payment $payment)
    {
    $this->middleware(['permission:payments_get'])->only(['getAllPublicPayments','getAllPrivatePayments','getAllPublicPrivatePayments']);

    // $this->payment = $payment;
    }
    //for user
    public function getAllPublicPayments(){
        try{
        $payments=Payment::where(['type'=>0])->get();
        return response()->json(['status'=>true,'message'=>'data has been getten successfully','data'=>$payments],200);
    

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        }
        public function getAllPrivatePayments(){
            try{
                $payments=Payment::where(['type'=>1])->get();
                 return response()->json(['status'=>true,'message'=>'data has been getten successfully','data'=>$payments],200);
    
                        
            }catch(\Exception $ex){
                return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
            } 
            }
    
            public function getAllPublicPrivatePayments(){
                try{
                    $payments=Payment::get();
                    return response()->json(['status'=>true,'message'=>'data has been getten successfully','data'=>$payments],200);
    
                            
                    }catch(\Exception $ex){
                        return response()->json(['status'=>false,'message'=>config('constants.error')],500);
            
                    } 
                }
                

}
