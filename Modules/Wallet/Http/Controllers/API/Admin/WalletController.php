<?php

namespace Modules\Wallet\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
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
        try{
            $wallets=$this->walletRepo->all($this->wallet);
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallets],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllPaginates(Request $request){
        try{
    $wallets=$this->walletRepo->getAllPaginates($this->wallet,$request);
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallets],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
  public function countData(){
        $countData=$this->walletRepo->countData($this->wallet);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countData],200);
          
     }



    // methods for trash
    public function trash(Request $request){
        try{
             $wallets=$this->walletRepo->trash($this->wallet,$request);
        if(is_string($wallets)){
            return response()->json(['status'=>false,'message'=>$wallets],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallets],200);

        
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
    public function store(StoreWalletRequest $request)
    {
        try{
        $wallet=$this->walletRepo->store($request,$this->wallet);
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallet],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
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
            $wallet=$this->walletRepo->find($id,$this->wallet);
    
        if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }
   
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallet],200);

        
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
    public function update(UpdateWalletRequest $request,$id)
    {
        try{
    $wallet= $this->walletRepo->update($request,$id,$this->wallet);
    if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }
   
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallet],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

    }

  

    //methods for restoring
    public function restore($id){
        try{
            $wallet =  $this->walletRepo->restore($id,$this->wallet);
             if(is_string($wallet)){
                    return response()->json(['status'=>false,'message'=>$wallet],404);
                }
   
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallet],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        

    }
    public function restoreAll(){
        try{
    $wallets =  $this->walletRepo->restoreAll($this->wallet);
     if(is_string($wallets)){
            return response()->json(['status'=>false,'message'=>$wallets],404);
        }
        
          
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallets],200);

        
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
    public function destroy(DeleteWalletRequest $request,$id)
    {
        try{
    $wallet= $this->walletRepo->destroy($id,$this->wallet);
     if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }
          
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallet],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function forceDelete(DeleteWalletRequest $request,$id)
    {
        try{
    //to make force destroy for a Wallet must be this Wallet  not found in Wallets table  , must be found in trash Wallets
    $wallet=$this->walletRepo->forceDelete($id,$this->wallet);
     if(is_string($wallet)){
            return response()->json(['status'=>false,'message'=>$wallet],404);
        }

          
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$wallet],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    
    
   
}
