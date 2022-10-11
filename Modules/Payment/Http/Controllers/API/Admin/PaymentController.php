<?php

namespace Modules\Payment\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Http\Requests\StorePaymentRequest;
use Modules\Payment\Http\Requests\UpdatePaymentRequest;
use Modules\Payment\Http\Requests\DeletePaymentRequest;
use Modules\Payment\Repositories\Admin\PaymentRepository;

class PaymentController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var PaymentRepository
    */
    protected $paymentRepo;
    /**
    * @var Payment
    */
    protected $payment;


    /**
    * PaymentsController constructor.
    *
    * @param PaymentRepository $payments
    */
    public function __construct(BaseRepository $baseRepo, Payment $payment,PaymentRepository $paymentRepo)
    {
    $this->middleware(['permission:payments_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:payments_trash'])->only('trash');
    $this->middleware(['permission:payments_restore'])->only('restore');
    $this->middleware(['permission:payments_restore-all'])->only('restore-all');
    $this->middleware(['permission:payments_show'])->only('show');
    $this->middleware(['permission:payments_store'])->only('store');
    $this->middleware(['permission:payments_update'])->only('update');
    $this->middleware(['permission:payments_destroy'])->only('destroy');
    $this->middleware(['permission:payments_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->payment = $payment;
    $this->paymentRepo = $paymentRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
    
    $payments=$this->paymentRepo->all($this->payment);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Payments has been getten successfully',
        'data'=> $payments
    ]);
    }

    public function getAllPaginates(Request $request){
    
    $payments=$this->paymentRepo->getAllPaginates($this->payment,$request);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Payments has been getten successfully(pagination)',
        'data'=> $payments
    ]);
    }




    // methods for trash
    public function trash(Request $request){
    $payments=$this->paymentRepo->trash($this->payment,$request);

    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Payments has been getten successfully (in trash)',
        'data'=> $payments
    ]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StorePaymentRequest $request)
    {
    $payment=$this->paymentRepo->store($request,$this->payment);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Payment has been stored successfully',
        'data'=> $payment
    ]);
    }
    

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
    $payment=$this->paymentRepo->find($id,$this->payment);
    
        if(is_string($payment)){
            return response()->json(['status'=>false,'message'=>$payment],404);
        }
   
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'Payment has been getten successfully',
            'data'=> $payment
        ]);
    
    }



    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(UpdatePaymentRequest $request,$id)
    {
    $payment= $this->paymentRepo->update($request,$id,$this->payment);
    if(is_string($payment)){
            return response()->json(['status'=>false,'message'=>$payment],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Payment has been updated successfully',
        'data'=> $payment
    ]);
    

    }

    public function inventory(){
    $paymentsInInventory= $this->paymentRepo->paymentsInInventory($this->payment);
    if(empty($paymentsInInventory)){
    if(is_string($payment)){
            return response()->json(['status'=>false,'message'=>$payment],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'PaymentsInInventory getting successfully',
        'data'=> $paymentsInInventory
    ]);
     
    }
    }

    //methods for restoring
    public function restore($id){
    
    $payment =  $this->paymentRepo->restore($id,$this->payment);
     if(is_string($payment)){
            return response()->json(['status'=>false,'message'=>$payment],404);
        }
    
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'Payment has been restored',
                'data'=> $payment
            ]);
        

    }
    public function restoreAll(){
    $payments =  $this->paymentRepo->restoreAll($this->payment);
     if(is_string($payments)){
            return response()->json(['status'=>false,'message'=>$payments],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'restored successfully',
            'data'=> $payments
        ]);
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeletePaymentRequest $request,$id)
    {
    $payment= $this->paymentRepo->destroy($id,$this->payment);
     if(is_string($payment)){
            return response()->json(['status'=>false,'message'=>$payment],404);
        }
  
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'destroyed  successfully',
        'data'=> $payment
    ]); 
    
    }
    public function forceDelete(DeletePaymentRequest $request,$id)
    {
    //to make force destroy for a Payment must be this Payment  not found in Payments table  , must be found in trash Payments
    $payment=$this->paymentRepo->forceDelete($id,$this->payment);
     if(is_string($payment)){
            return response()->json(['status'=>false,'message'=>$payment],404);
        }

        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed forcely successfully ',
            'data'=> null
        ]); 
    
    }
    
    
   
}
