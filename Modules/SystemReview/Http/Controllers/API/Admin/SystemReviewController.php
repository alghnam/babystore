<?php

namespace Modules\SystemReview\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SystemReview\Entities\SystemReview;
use Modules\SystemReview\Http\Requests\StoreSystemReviewRequest;
use Modules\SystemReview\Http\Requests\UpdateSystemReviewRequest;
use Modules\SystemReview\Http\Requests\DeleteSystemReviewRequest;
use Modules\SystemReview\Repositories\Admin\SystemReviewRepository;

class SystemReviewController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var SystemReviewRepository
    */
    protected $systemReviewRepo;
    /**
    * @var SystemReview
    */
    protected $systemReview;


    /**
    * SystemReviewsController constructor.
    *
    * @param SystemReviewRepository $systemReviews
    */
    public function __construct(BaseRepository $baseRepo, SystemReview $systemReview,SystemReviewRepository $systemReviewRepo)
    {
    $this->middleware(['permission:system_reviews_read'])->only([['index','getAllPaginates']]);
    $this->middleware(['permission:system_reviews_trash'])->only('trash');
    $this->middleware(['permission:system_reviews_restore'])->only('restore');
    $this->middleware(['permission:system_reviews_restore-all'])->only('restore-all');
    $this->middleware(['permission:system_reviews_show'])->only('show');
    $this->middleware(['permission:system_reviews_store'])->only('store');
    $this->middleware(['permission:system_reviews_update'])->only('update');
    $this->middleware(['permission:system_reviews_destroy'])->only('destroy');
    $this->middleware(['permission:system_reviews_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->systemReview = $systemReview;
    $this->systemReviewRepo = $systemReviewRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
            $systemReviews=$this->systemReviewRepo->all($this->systemReview);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviews],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
  public function countData(){
        $countData=$this->systemReviewRepo->countData($this->systemReview);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countData],200);
          
     }
    public function getAllPaginates(Request $request){
        try{
            $systemReviews=$this->systemReviewRepo->getAllPaginates($this->systemReview,$request);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviews],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
            $systemReviews=$this->systemReviewRepo->trash($this->systemReview,$request);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviews],200);

        
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
    public function store(StoreSystemReviewRequest $request)
    {
        try{
            $systemReview=$this->systemReviewRepo->store($request,$this->systemReview);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReview],200);

        
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
    $systemReview=$this->systemReviewRepo->find($id,$this->systemReview);
    
        if(is_string($systemReview)){
            return response()->json(['status'=>false,'message'=>$systemReview],404);
        }
   
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReview],200);

        
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
    public function update(UpdateSystemReviewRequest $request,$id)
    {
        try{
    $systemReview= $this->systemReviewRepo->update($request,$id,$this->systemReview);
    if(is_string($systemReview)){
            return response()->json(['status'=>false,'message'=>$systemReview],404);
        }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReview],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

    }


    //methods for restoring
    public function restore($id){
        try{
            $systemReview =  $this->systemReviewRepo->restore($id,$this->systemReview);
             if(is_string($systemReview)){
                    return response()->json(['status'=>false,'message'=>$systemReview],404);
                }
    
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReview],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
    $systemReviews =  $this->systemReviewRepo->restoreAll($this->systemReview);
    try{
         if(is_string($systemReviews)){
                return response()->json(['status'=>false,'message'=>$systemReviews],404);
            }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReviews],200);

        
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
    public function destroy(DeleteSystemReviewRequest $request,$id)
    {
        try{
            $systemReview= $this->systemReviewRepo->destroy($id,$this->systemReview);
             if(is_string($systemReview)){
                    return response()->json(['status'=>false,'message'=>$systemReview],404);
                }
  
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReview],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    public function forceDelete(DeleteSystemReviewRequest $request,$id)
    {
        try{
            //to make force destroy for a SystemReview must be this SystemReview  not found in SystemReviews table  , must be found in trash SystemReviews
            $systemReview=$this->systemReviewRepo->forceDelete($id,$this->systemReview);
             if(is_string($systemReview)){
                    return response()->json(['status'=>false,'message'=>$systemReview],404);
                }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$systemReview],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
    
   
}
