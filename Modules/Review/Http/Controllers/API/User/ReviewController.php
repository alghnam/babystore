<?php

namespace Modules\Review\Http\Controllers\API\User;


use  Modules\Review\Entities\Review;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Comment\Http\Requests\DeleteReviewRequest;
use Modules\Review\Http\Requests\StoreReviewRequest;
use Modules\Review\Http\Requests\UpdateReviewRequest;
use Modules\Review\Repositories\ReviewRepository;
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
        $this->middleware(['permission:reviews_add'])->only('addReview');
        $this->baseRepo = $baseRepo;
        $this->review = $review;
        $this->reviewRepo = $reviewRepo;
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
