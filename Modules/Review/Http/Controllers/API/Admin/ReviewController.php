<?php

namespace Modules\Review\Http\Controllers\API\Admin;


use  Modules\Review\Entities\Review;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Review\Http\Requests\DeleteReviewRequest;
use Modules\Review\Http\Requests\StoreReviewRequest;
use Modules\Review\Http\Requests\UpdateReviewRequest;
use Modules\Review\Repositories\Admin\ReviewRepository;
use Modules\Review\Http\Requests\AddReviewRequest;

class ReviewController extends Controller
{
        /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var ReviewRepository
     */
    protected $reviewRepo;
        /**
     * @var Review
     */
    protected $review;
   

    /**
     * ReviewsController constructor.
     *
     * @param ReviewRepository $reviews
     */
    public function __construct(BaseRepository $baseRepo, Review $review,ReviewRepository $reviewRepo)
    {
        $this->middleware(['permission:reviews_read'])->only(['index','getAllPaginates']);
        $this->middleware(['permission:reviews_trash'])->only('trash');
        $this->middleware(['permission:reviews_restore'])->only('restore');
        $this->middleware(['permission:reviews_restore-all'])->only('restore-all');
        $this->middleware(['permission:reviews_show'])->only('show');
        $this->middleware(['permission:reviews_store'])->only('store');
        $this->middleware(['permission:reviews_update'])->only('update');
        $this->middleware(['permission:reviews_destroy'])->only('destroy');
        $this->middleware(['permission:reviews_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->review = $review;
        $this->reviewRepo = $reviewRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
          try{
        $reviews=$this->reviewRepo->all($this->review);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reviews],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllPaginates(Request $request){
        
          try{
        $reviews=$this->reviewRepo->getAllPaginates($this->review,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reviews],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
      public function countData(){
        $countData=$this->reviewRepo->countData($this->review);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countData],200);
          
     }
        public function getReviewsProduct(Request $request,$productId){
        
          try{
        $reviewsProduct=$this->reviewRepo->getReviewsProduct($this->review,$request,$productId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reviewsProduct],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
          try{
        $reviews=$this->reviewRepo->trash($this->review,$request);
                if(is_string($reviews)){
            return response()->json(['status'=>false,'message'=>$reviews],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reviews],200);

        
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
    public function store(StoreReviewRequest $request)
    {
         try{
       $review= $this->reviewRepo->store($request,$this->review);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$review],200);

        
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
        $review=$this->reviewRepo->find($id,$this->review);
                 if(is_string($review)){
            return response()->json(['status'=>false,'message'=>$review],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$review],200);

        
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
    public function update(UpdateReviewRequest $request,$id)
    {
          try{
       $review= $this->reviewRepo->update($request,$id,$this->review);
                        if(is_string($review)){
            return response()->json(['status'=>false,'message'=>$review],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$review],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }

    //methods for restoring
    public function restore($id){
        
          try{
        $review =  $this->reviewRepo->restore($id,$this->review);
                         if(is_string($review)){
            return response()->json(['status'=>false,'message'=>$review],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$review],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
           

    }
    public function restoreAll(){
          try{
        $reviews =  $this->reviewRepo->restoreAll($this->review);
                      if(is_string($reviews)){
            return response()->json(['status'=>false,'message'=>$reviews],404);
        }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reviews],200);


        
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
    public function destroy(DeleteReviewRequest $request,$id)
    {
         try{
       $review= $this->reviewRepo->destroy($id,$this->review);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$review],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function forceDelete(DeleteReviewRequest $request,$id)
    {
          try{
        //to make force destroy for a Review must be this Review  not found in Reviews table  , must be found in trash Reviews
        $review=$this->reviewRepo->forceDelete($id,$this->review);
                 if(is_string($review)){
            return response()->json(['status'=>false,'message'=>$review],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$review],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
    }
        public function addReview(AddReviewRequest $request,$productId)
    {
         try{
       $review= $this->reviewRepo->addReview($request,$this->review,$productId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$review],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

}
