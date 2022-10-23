<?php

namespace Modules\Payment\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
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
        try{
            $payments=$this->paymentRepo->all($this->payment);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payments],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllPaginates(Request $request){
        try{
            $payments=$this->paymentRepo->getAllPaginates($this->payment,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payments],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
            $payments=$this->paymentRepo->trash($this->payment,$request);

            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payments],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StorePaymentRequest $request)
    {
        // try{
            $payment=$this->paymentRepo->store($request,$this->payment);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payment],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }
    

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        try{
            $payment=$this->paymentRepo->find($id,$this->payment);
        
            if(is_string($payment)){
                return response()->json(['status'=>false,'message'=>$payment],404);
            }
       
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payment],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
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
        try{
            $payment= $this->paymentRepo->update($request,$id,$this->payment);
            if(is_string($payment)){
                    return response()->json(['status'=>false,'message'=>$payment],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payment],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }

    

    //methods for restoring
    public function restore($id){
        try{
            $payment =  $this->paymentRepo->restore($id,$this->payment);
             if(is_string($payment)){
                    return response()->json(['status'=>false,'message'=>$payment],404);
                }
    
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payment],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
        try{
            $payments =  $this->paymentRepo->restoreAll($this->payment);
             if(is_string($payments)){
                    return response()->json(['status'=>false,'message'=>$payments],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payments],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeletePaymentRequest $request,$id)
    {
        try{
            $payment= $this->paymentRepo->destroy($id,$this->payment);
             if(is_string($payment)){
                    return response()->json(['status'=>false,'message'=>$payment],404);
                }
  
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payment],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    public function forceDelete(DeletePaymentRequest $request,$id)
    {
        try{
            //to make force destroy for a Payment must be this Payment  not found in Payments table  , must be found in trash Payments
            $payment=$this->paymentRepo->forceDelete($id,$this->payment);
             if(is_string($payment)){
                    return response()->json(['status'=>false,'message'=>$payment],404);
                }

            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$payment],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    
    
   
}
