<?php

namespace Modules\Payment\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Payment;
class PaymentController extends Controller
{
    //for user
    public function getAllPublicPayments(){
        $payments=Payment::where(['type'=>0])->get();
                            return response()->json(['status'=>true,'message'=>'data has been getten successfully','data'=>$payments],200);
    }
        public function getAllPrivatePayments(){
        $payments=Payment::where(['type'=>1])->get();
                            return response()->json(['status'=>true,'message'=>'data has been getten successfully','data'=>$payments],200);
    }
    
            public function getAllPublicPrivatePayments(){
        $payments=Payment::get();
                            return response()->json(['status'=>true,'message'=>'data has been getten successfully','data'=>$payments],200);
    }
}
