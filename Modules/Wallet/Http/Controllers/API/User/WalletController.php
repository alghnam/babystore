<?php

namespace Modules\Wallet\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\Wallet;
use Modules\Movement\Entities\Movement;
use Modules\Wallet\Http\Requests\AddToWalletRequest;
use Modules\Wallet\Repositories\User\WalletRepository;
// use Modules\Movement\Repositories\MovementRepository;
use Modules\Wallet\Http\Requests\FinishingPaymentRequest;
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
    
    //     /**
    //  * @var MovementRepository
    //  */
    // protected $movementRepo;
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
                            //  $this->middleware(['permission:wallets_add'])->only('addToWallet');
                            //  $this->middleware(['permission:wallets_finish'])->only('finishingPayment');
                            //  $this->middleware(['permission:wallets_balance'])->only('balanceWallet');

        
        $this->baseRepo = $baseRepo;
        $this->wallet = $wallet;
        $this->movement = $movement;
        $this->walletRepo = $walletRepo;
    }

    ///for user
    public function addToWallet(AddToWalletRequest $request){
        $movementWallet=$this->walletRepo->AddToWallet($this->wallet,$this->movement,$request);
     //   dd($movementWallet);
       if(is_string($movementWallet)){
                    return response()->json(['status'=>false,'message'=>$movementWallet],400);
                }
                // dd($movementWallet);
            $data=[
                'movementWallet'=>$movementWallet['movementWallet'],
                'resPayment'=>$movementWallet['resPayment']
                ];
            return response()->json([
                'status'=>true,
                'code' => 200,
                // 'message' => 'amount has been added successfully',
                'data'=>  $data
            ]);
        
    }
        //لازم يكون مدخل بالانبوتس عشان تطلعلي نتيجة بالايدي 
    //فتاع التطبيق بس عليه بالاول يختاروسيلة الدفع ويضغط ع انهاء الطلب بيوديه اللي هو هلا صار متخزن ايدي تاع الوسيلة اللي حيتحمل هنا ع هاددا الراوت اللي حيستدعى من الكارد اللي حتظهرله بعد م يضعط ع انهاء الطلب 
    //حيعبي اللي فيها ويضغط ع دفع الان ليشوف النتيجة 
    
    public function getPaymentStatus($id){
            // 	 Storage::put('paymentStatusId',null);

        $url = "https://eu-test.oppwa.com/v1/checkouts/".$id."/payment";
        $url .= "?entityId=8a8294174b7ecb28014b9699220015ca";
        
        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                           'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	$responseData = curl_exec($ch);
        	if(curl_errno($ch)) {
        		return curl_error($ch);
        	}
        	curl_close($ch);
    	$paymentStatus= json_decode($responseData,true);
    	return $paymentStatus;
    	  //store id in db from $paymentStatus 
      //$order->this order that click on it to finishing itfrom method finishingOrder
    //             $orderIdFinished=  Storage::get('orderIdFinished');
    //     $order=Order::where(['id'=>$orderIdFinished])->first();
    //   $order->bank_transaction_id=$paymentStatus['id'];
    //   $order->save();
    	 if(isset($paymentStatus['id'])){//payment success
    	 Storage::put('paymentStatusId',$paymentStatus['id']);
            return true;
            
        }else{
            return false;
        }
    	
    
       
    }
    public function finishingPayment(){
         $movementWallet=$this->walletRepo->finishingPayment($this->wallet,$this->movement);
                                         if(is_string($movementWallet)){
                    return response()->json(['status'=>false,'message'=>$movementWallet],400);
                }

            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'your payment has been finished',
                'data'=>  $movementWallet
            ]);   
    }
    public function balanceWallet(){
                $wallet=$this->walletRepo->balanceWallet($this->wallet);
            $data=[
                'amount'=>$wallet->amount
                ];
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'balance wallet  has been getten successfully',
                'data'=>  $data
            ]);
    }
    
}
