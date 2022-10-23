<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Routing\Controller;
class PaymentController extends Controller
{
       

                
    public function getVisaForm(){
        return view('payments.visa');
        
    }
}
