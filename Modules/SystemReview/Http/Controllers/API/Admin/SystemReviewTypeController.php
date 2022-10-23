<?php

namespace Modules\SystemReview\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SystemReview\Entities\SystemReviewType;
use Modules\SystemReview\Http\Requests\Type\StoreSystemReviewTypeRequest;
use Modules\SystemReview\Http\Requests\Type\UpdateSystemReviewTypeRequest;
use Modules\SystemReview\Http\Requests\Type\DeleteSystemReviewTypeRequest;
use Modules\SystemReview\Repositories\Admin\Type\SystemReviewTypeRepository;

class SystemReviewTypeController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var SystemReviewTypeRepository
    */
    protected $systemReviewTypeRepo;
    /**
    * @var SystemReview
    */
    protected $systemReviewType;


    /**
    * SystemReviewsController constructor.
    *
    * @param SystemReviewTypeRepository $systemReviewTypes
    */
    public function __construct(BaseRepository $baseRepo, SystemReviewType $systemReviewType,SystemReviewTypeRepository $systemReviewTypeRepo)
    {
//     $this->middleware(['permission:system_review_types_read'])->only(['index','getAllPaginates']);
// $this->middleware(['permission:system_review_types_trash'])->only('trash');
//     $this->middleware(['permission:system_review_types_restore'])->only('restore');
//     $this->middleware(['permission:system_review_types_restore-all'])->only('restore-all');
//     $this->middleware(['permission:system_review_types_show'])->only('show');
//     $this->middleware(['permission:system_review_types_store'])->only('store');
//     $this->middleware(['permission:system_review_types_update'])->only('update');
//     $this->middleware(['permission:system_review_types_destroy'])->only('destroy');
//     $this->middleware(['permission:system_review_types_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->systemReviewType = $systemReviewType;
    $this->systemReviewTypeRepo = $systemReviewTypeRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
    
    $systemReviewTypes=$this->systemReviewTypeRepo->all($this->systemReviewType);
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewTypes],200);

    }

    public function getAllPaginates(Request $request){
    
    $systemReviewTypes=$this->systemReviewTypeRepo->getAllPaginates($this->systemReviewType,$request);
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewTypes],200);

    }




    // methods for trash
    public function trash(Request $request){
    $systemReviewTypes=$this->systemReviewTypeRepo->trash($this->systemReviewType,$request);

              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewTypes],200);

    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreSystemReviewTypeRequest $request)
    {
    $systemReviewType=$this->systemReviewTypeRepo->store($request,$this->systemReviewType);
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewType],200);

    }
    

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
    $systemReviewType=$this->systemReviewTypeRepo->find($id,$this->systemReviewType);
    
        if(is_string($systemReviewType)){
            return response()->json(['status'=>false,'message'=>$systemReviewType],404);
        }
   
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewType],200);

    }



    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateSystemReviewTypeRequest $request,$id)
    {
    $systemReviewType= $this->systemReviewTypeRepo->update($request,$id,$this->systemReviewType);
    if(is_string($systemReviewType)){
            return response()->json(['status'=>false,'message'=>$systemReviewType],404);
        }
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewType],200);

    

    }

   

    //methods for restoring
    public function restore($id){
    
    $systemReviewType =  $this->systemReviewTypeRepo->restore($id,$this->systemReviewType);
     if(is_string($systemReviewType)){
            return response()->json(['status'=>false,'message'=>$systemReviewType],404);
        }
    
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewType],200);

        

    }
    public function restoreAll(){
    $systemReviewTypes =  $this->systemReviewTypeRepo->restoreAll($this->systemReviewType);
     if(is_string($systemReviewTypes)){
            return response()->json(['status'=>false,'message'=>$systemReviewTypes],404);
        }
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewType],200);

    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteSystemReviewTypeRequest $request,$id)
    {
    $systemReviewType= $this->systemReviewTypeRepo->destroy($id,$this->systemReviewType);
     if(is_string($systemReviewType)){
            return response()->json(['status'=>false,'message'=>$systemReviewType],404);
        }
  
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewType],200);

    
    }
    public function forceDelete(DeleteSystemReviewTypeRequest $request,$id)
    {
    //to make force destroy for a SystemReview must be this SystemReview  not found in SystemReviews table  , must be found in trash SystemReviews
    $systemReviewType=$this->systemReviewTypeRepo->forceDelete($id,$this->systemReviewType);
     if(is_string($systemReviewType)){
            return response()->json(['status'=>false,'message'=>$systemReviewType],404);
        }
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviewTypes],200);

    
    }
    
    
   
}
