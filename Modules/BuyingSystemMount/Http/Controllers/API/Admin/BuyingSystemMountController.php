<?php

namespace Modules\BuyingSystemMount\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BuyingSystemMount\Entities\BuyingSystemMount;
use Modules\BuyingSystemMount\Http\Requests\UpdateBuyingSystemMountRequest;
use Modules\BuyingSystemMount\Repositories\Admin\BuyingSystemMountRepository;

class BuyingSystemMountController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var BuyingSystemMountRepository
    */
    protected $BuyingSystemMountRepo;
    /**
    * @var BuyingSystemMount
    */
    protected $BuyingSystemMount;


    /**
    * WalletsController constructor.
    *
    * @param WalletRepository $wallets
    */
    public function __construct(BaseRepository $baseRepo, BuyingSystemMount $buyingSystemMount,BuyingSystemMountRepository $buyingSystemMountRepo)
    {
    $this->middleware(['permission:buying_system_mounts_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:buying_system_mounts_trash'])->only('trash');
    $this->middleware(['permission:buying_system_mounts_restore'])->only('restore');
    $this->middleware(['permission:buying_system_mounts_restore-all'])->only('restore-all');
    $this->middleware(['permission:buying_system_mounts_show'])->only('show');
    $this->middleware(['permission:buying_system_mounts_store'])->only('store');
    $this->middleware(['permission:buying_system_mounts_update'])->only('update');
    $this->middleware(['permission:buying_system_mounts_destroy'])->only('destroy');
    $this->middleware(['permission:buying_system_mounts_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->buyingSystemMount = $buyingSystemMount;
    $this->buyingSystemMountRepo = $buyingSystemMountRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
            $buyingSystemMounts=$this->buyingSystemMountRepo->all($this->buyingSystemMount);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$buyingSystemMounts],200);
    
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllPaginates(Request $request){
        try{
            
            $buyingSystemMounts=$this->buyingSystemMountRepo->getAllPaginates($this->buyingSystemMount,$request);
        return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$buyingSystemMounts],200);
        
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
    public function update(UpdateBuyingSystemMountRequest $request,$id)
    {
        // try{
            $buyingSystemMount= $this->buyingSystemMountRepo->update($request,$id,$this->buyingSystemMount);
            if(is_string($buyingSystemMount)){
                    return response()->json(['status'=>false,'message'=>$buyingSystemMount],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$buyingSystemMount],200);
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    

    }

}
