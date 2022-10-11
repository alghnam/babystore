<?php

namespace Modules\UpSell\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\UpSell\Entities\UpSell;
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
    $this->middleware(['permission:upsells_read'])->only([['index','getAllPaginates']]);
    $this->middleware(['permission:upsells_trash'])->only('trash');
    $this->middleware(['permission:upsells_restore'])->only('restore');
    $this->middleware(['permission:upsells_restore-all'])->only('restore-all');
    $this->middleware(['permission:upsells_show'])->only('show');
    $this->middleware(['permission:upsells_store'])->only('store');
    $this->middleware(['permission:upsells_update'])->only('update');
    $this->middleware(['permission:upsells_destroy'])->only('destroy');
    $this->middleware(['permission:upsells_destroy-force'])->only('destroy-force');
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
    
    $upsells=$this->upsellRepo->all($this->upsell);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSells has been getten successfully',
        'data'=> $upsells
    ]);
    }

    public function getAllPaginates(Request $request){
    
    $upsells=$this->upsellRepo->getAllPaginates($this->upsell,$request);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSells has been getten successfully(pagination)',
        'data'=> $upsells
    ]);
    }



    public function upsellsProduct($productId){
           $upsellsProduct=$this->upsellRepo->getUpsellsProduct($productId);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSells product has been getten successfully',
        'data'=> $upsellsProduct
    ]); 
    }
    
    public function deleteUpsellProduct($productId,$upsellId){
           $upsellProduct=$this->upsellRepo->deleteUpsellProduct($this->upsell,$productId,$upsellId);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSells product has been deleted successfully',
        'data'=> $upsellProduct
    ]); 
    }
    // methods for trash
    public function trash(Request $request){
    $upsells=$this->upsellRepo->trash($this->upsell,$request);

    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSells has been getten successfully (in trash)',
        'data'=> $upsells
    ]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreUpSellRequest $request)
    {
    $upsell=$this->upsellRepo->store($request,$this->upsell);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSell has been stored successfully',
        'data'=> $upsell
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
    $upsell=$this->upsellRepo->find($id,$this->upsell);
    
        if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
   
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'UpSell has been getten successfully',
            'data'=> $upsell
        ]);
    
    }



    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateDetailsUpSellRequest $request,$id)
    {
    $upsell= $this->upsellRepo->update($request,$id,$this->upsell);
    if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSell has been updated successfully',
        'data'=> $upsell
    ]);
    

    }
    
        public function updateUpsellsProduct(UpdateUpSellRequest $request,$id)
    {
    $upsell= $this->upsellRepo->updateUpsellsProduct($request,$id,$this->upsell);
    if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSell has been updated successfully',
        'data'=> $upsell
    ]);
    

    }

    public function inventory(){
    $upsellsInInventory= $this->upsellRepo->upsellsInInventory($this->upsell);
    if(empty($upsellsInInventory)){
    if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'UpSellsInInventory getting successfully',
        'data'=> $upsellsInInventory
    ]);
     
    }
    }

    //methods for restoring
    public function restore($id){
    
    $upsell =  $this->upsellRepo->restore($id,$this->upsell);
     if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
    
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'UpSell has been restored',
                'data'=> $upsell
            ]);
        

    }
    public function restoreAll(){
    $upsells =  $this->upsellRepo->restoreAll($this->upsell);
     if(is_string($upsells)){
            return response()->json(['status'=>false,'message'=>$upsells],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'restored successfully',
            'data'=> $upsells
        ]);
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteUpSellRequest $request,$id)
    {
    $upsell= $this->upsellRepo->destroy($id,$this->upsell);
     if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }
  
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'destroyed  successfully',
        'data'=> $upsell
    ]); 
    
    }
    public function forceDelete(DeleteUpSellRequest $request,$id)
    {
    //to make force destroy for a UpSell must be this UpSell  not found in UpSells table  , must be found in trash UpSells
    $upsell=$this->upsellRepo->forceDelete($id,$this->upsell);
     if(is_string($upsell)){
            return response()->json(['status'=>false,'message'=>$upsell],404);
        }

        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed forcely successfully ',
            'data'=> null
        ]); 
    
    }
    
    
   
}
