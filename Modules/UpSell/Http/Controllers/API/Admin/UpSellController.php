<?php

namespace Modules\UpSell\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\UpSell\Entities\UpSell;
use Modules\Product\Entities\Product;
use Modules\UpSell\Http\Requests\StoreUpSellRequest;
use Modules\UpSell\Http\Requests\UpdateUpSellRequest;
use Modules\UpSell\Http\Requests\DeleteUpSellRequest;
use Modules\UpSell\Http\Requests\UpdateDetailsUpSellRequest;
use Modules\UpSell\Repositories\Admin\UpSellRepository;
class UpSellController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var UpSellRepository
    */
    protected $upsellRepo;
    /**
    * @var UpSell
    */
    protected $upsell;


    /**
    * UpSellsController constructor.
    *
    * @param UpSellRepository $upsells
    */
    public function __construct(BaseRepository $baseRepo, UpSell $upsell,UpSellRepository $upsellRepo)
    {
    $this->middleware(['permission:up_sells_read'])->only([['index','getAllPaginates']]);
    $this->middleware(['permission:up_sells_trash'])->only('trash');
    $this->middleware(['permission:up_sells_restore'])->only('restore');
    $this->middleware(['permission:up_sells_restore-all'])->only('restore-all');
    $this->middleware(['permission:up_sells_show'])->only('show');
    $this->middleware(['permission:up_sells_store'])->only('store');
    $this->middleware(['permission:up_sells_update'])->only('update');
    $this->middleware(['permission:up_sells_destroy'])->only('destroy');
    $this->middleware(['permission:up_sells_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->upsell = $upsell;
    $this->upsellRepo = $upsellRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
            $upsells=$this->upsellRepo->all($this->upsell);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsells],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
     public function countData(){
        $countData=$this->upsellRepo->countData($this->upsell);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countData],200);
          
     }
    public function getAllPaginates(Request $request){
        // try{
            $upsells=$this->upsellRepo->getAllPaginates($this->upsell,$request);
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsells],200);

        
    //     }catch(\Exception $ex){
    //         return response()->json(['status'=>false,'message'=>config('constants.error')],500);

    // }

}

    public function upsellsProduct($productId){
        // try{
           $upsellsProduct=$this->upsellRepo->getUpsellsProduct($productId);
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsellsProduct],200);

        
    //     }catch(\Exception $ex){
    //         return response()->json(['status'=>false,'message'=>config('constants.error')],500);

    // }
    }
    
    public function deleteUpsellProduct($id,$upsellId){
        // try{
           $upsellProduct=$this->upsellRepo->deleteUpsellProduct($this->upsell,$id,$upsellId);
           if(is_string($upsellProduct)){
            return response()->json(['status'=>false,'message'=>$upsellProduct],404);
        }
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsellProduct],200);

        
    //     }catch(\Exception $ex){
    //         return response()->json(['status'=>false,'message'=>config('constants.error')],500);

    // }
    }
    // methods for trash
    public function trash(Request $request){
        try{
        $upsells=$this->upsellRepo->trash($this->upsell,$request);
    
            if(is_string($upsells)){
                return response()->json(['status'=>false,'message'=>$upsells],404);
            }
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsells],200);
    
            
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
    public function store(StoreUpSellRequest $request)
    {
        // try{
            $upsell=$this->upsellRepo->store($request,$this->upsell);
             if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],400);
        }
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsell],200);

        
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
    $upsell=$this->upsellRepo->find($id,$this->upsell);
    
        if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
   
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsell->load('product')],200);

        
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
    public function update(UpdateDetailsUpSellRequest $request,$id,$productId)
    {
        // try{
            $upsell= $this->upsellRepo->updateUpsellsPro($request,$id,$productId,$this->upsell);
            if(is_string($upsell)){
                    return response()->json(['status'=>false,'message'=>$upsell],400);
                }
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsell],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    

    }
    
        public function updateUpsellsProduct(UpdateUpSellRequest $request,$productId)
    {
        // try{
                
    $upsell= $this->upsellRepo->updateUpsellsProduct($request,$productId,$this->upsell);
    if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsell],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 

    }


    //methods for restoring
    public function restore($id){
        try{
            $upsell =  $this->upsellRepo->restore($id,$this->upsell);
             if(is_string($upsell)){
                    return response()->json(['status'=>false,'message'=>$upsell],404);
                }
    
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsell],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        

    }
    public function restoreAll(){
        try{
    $upsells =  $this->upsellRepo->restoreAll($this->upsell);
     if(is_string($upsells)){
            return response()->json(['status'=>false,'message'=>$upsells],404);
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsells],200);
        }
        
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
    public function destroy(DeleteUpSellRequest $request,$id)
    {
        try{
    $upsell= $this->upsellRepo->destroy($id,$this->upsell);
     if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsell],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    public function forceDelete(DeleteUpSellRequest $request,$id)
    {
        try{
    //to make force destroy for a UpSell must be this UpSell  not found in UpSells table  , must be found in trash UpSells
    $upsell=$this->upsellRepo->forceDelete($id,$this->upsell);
     if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$upsell],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    
    
   
}
