<?php

namespace Modules\Wallet\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Http\Requests\StoreWalletRequest;
use Modules\Wallet\Http\Requests\UpdateWalletRequest;
use Modules\Wallet\Http\Requests\DeleteWalletRequest;
use Modules\Wallet\Repositories\Admin\WalletRepository;

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
    * WalletsController constructor.
    *
    * @param WalletRepository $wallets
    */
    public function __construct(BaseRepository $baseRepo, Wallet $wallet,WalletRepository $walletRepo)
    {
    $this->middleware(['permission:wallets_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:wallets_trash'])->only('trash');
    $this->middleware(['permission:wallets_restore'])->only('restore');
    $this->middleware(['permission:wallets_restore-all'])->only('restore-all');
    $this->middleware(['permission:wallets_show'])->only('show');
    $this->middleware(['permission:wallets_store'])->only('store');
    $this->middleware(['permission:wallets_update'])->only('update');
    $this->middleware(['permission:wallets_destroy'])->only('destroy');
    $this->middleware(['permission:wallets_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->wallet = $wallet;
    $this->walletRepo = $walletRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
    
    $wallets=$this->walletRepo->all($this->wallet);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Wallets has been getten successfully',
        'data'=> $wallets
    ]);
    }

    public function getAllPaginates(Request $request){
    
    $wallets=$this->walletRepo->getAllPaginates($this->wallet,$request);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Wallets has been getten successfully(pagination)',
        'data'=> $wallets
    ]);
    }




    // methods for trash
    public function trash(Request $request){
    $wallets=$this->walletRepo->trash($this->wallet,$request);

    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Wallets has been getten successfully (in trash)',
        'data'=> $wallets
    ]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreWalletRequest $request)
    {
    $wallet=$this->walletRepo->store($request,$this->wallet);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Wallet has been stored successfully',
        'data'=> $wallet
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
    $wallet=$this->walletRepo->find($id,$this->wallet);
    
        if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }
   
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'Wallet has been getten successfully',
            'data'=> $wallet
        ]);
    
    }



    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateWalletRequest $request,$id)
    {
    $wallet= $this->walletRepo->update($request,$id,$this->wallet);
    if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Wallet has been updated successfully',
        'data'=> $wallet
    ]);
    

    }

    public function inventory(){
    $walletsInInventory= $this->walletRepo->walletsInInventory($this->wallet);
    if(empty($walletsInInventory)){
    if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'WalletsInInventory getting successfully',
        'data'=> $walletsInInventory
    ]);
     
    }
    }

    //methods for restoring
    public function restore($id){
    
    $wallet =  $this->walletRepo->restore($id,$this->wallet);
     if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }
    
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'Wallet has been restored',
                'data'=> $wallet
            ]);
        

    }
    public function restoreAll(){
    $wallets =  $this->walletRepo->restoreAll($this->wallet);
     if(is_string($wallets)){
            return response()->json(['status'=>false,'message'=>$wallets],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'restored successfully',
            'data'=> $wallets
        ]);
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteWalletRequest $request,$id)
    {
    $wallet= $this->walletRepo->destroy($id,$this->wallet);
     if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }
  
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'destroyed  successfully',
        'data'=> $wallet
    ]); 
    
    }
    public function forceDelete(DeleteWalletRequest $request,$id)
    {
    //to make force destroy for a Wallet must be this Wallet  not found in Wallets table  , must be found in trash Wallets
    $wallet=$this->walletRepo->forceDelete($id,$this->wallet);
     if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }

        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed forcely successfully ',
            'data'=> null
        ]); 
    
    }
    
    
   
}
