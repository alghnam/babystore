<?php

namespace Modules\Movement\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Movement\Entities\Movement;
use Modules\Movement\Http\Requests\StoreMovementRequest;
use Modules\Movement\Http\Requests\UpdateMovementRequest;
use Modules\Movement\Http\Requests\DeleteMovementRequest;
use Modules\Movement\Repositories\Admin\MovementRepository;

class MovementController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var MovementRepository
    */
    protected $movementRepo;
    /**
    * @var Movement
    */
    protected $movement;


    /**
    * MovementsController constructor.
    *
    * @param MovementRepository $movements
    */
    public function __construct(BaseRepository $baseRepo, Movement $movement,MovementRepository $movementRepo)
    {
    $this->middleware(['permission:movements_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:movements_trash'])->only('trash');
    $this->middleware(['permission:movements_restore'])->only('restore');
    $this->middleware(['permission:movements_restore-all'])->only('restore-all');
    $this->middleware(['permission:movements_show'])->only('show');
    $this->middleware(['permission:movements_store'])->only('store');
    $this->middleware(['permission:movements_update'])->only('update');
    $this->middleware(['permission:movements_destroy'])->only('destroy');
    $this->middleware(['permission:movements_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->movement = $movement;
    $this->movementRepo = $movementRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
            $movements=$this->movementRepo->all($this->movement);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movements],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllPaginates(Request $request){
    try{
        $movements=$this->movementRepo->getAllPaginates($this->movement,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movements],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
            $movements=$this->movementRepo->trash($this->movement,$request);
            if(is_string($movements)){
                return response()->json(['status'=>false,'message'=>$movements],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movements],200);

        
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
    public function store(StoreMovementRequest $request)
    {
        try{
            $movement=$this->movementRepo->store($request,$this->movement);
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movement],200);

        
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
            $movement=$this->movementRepo->find($id,$this->movement);
        
            if(is_string($movement)){
                return response()->json(['status'=>false,'message'=>$movement],404);
            }
       
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movement],200);

        
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
    public function update(UpdateMovementRequest $request,$id)
    {
        try{
            $movement= $this->movementRepo->update($request,$id,$this->movement);
            if(is_string($movement)){
                    return response()->json(['status'=>false,'message'=>$movement],404);
                }
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movement],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

    }

   

    //methods for restoring
    public function restore($id){
        try{
            $movement =  $this->movementRepo->restore($id,$this->movement);
             if(is_string($movement)){
                    return response()->json(['status'=>false,'message'=>$movement],404);
                }
    
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movement],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        

    }
    public function restoreAll(){
        try{
            $movements =  $this->movementRepo->restoreAll($this->movement);
             if(is_string($movements)){
                    return response()->json(['status'=>false,'message'=>$movements],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movements],200);

        
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
    public function destroy(DeleteMovementRequest $request,$id)
    {
        try{
        $movement= $this->movementRepo->destroy($id,$this->movement);
         if(is_string($movement)){
                return response()->json(['status'=>false,'message'=>$movement],404);
            }
  
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movement],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    public function forceDelete(DeleteMovementRequest $request,$id)
    {
    //to make force destroy for a Movement must be this Movement  not found in Movements table  , must be found in trash Movements
    $movement=$this->movementRepo->forceDelete($id,$this->movement);
     if(is_string($movement)){
            return response()->json(['status'=>false,'message'=>$movement],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$movement],200);

    
    }
    
    
   
}
