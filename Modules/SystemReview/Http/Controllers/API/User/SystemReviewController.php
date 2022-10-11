<?php

namespace Modules\SystemReview\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SystemReview\Http\Requests\AddSystemReviewRequest;
use Modules\SystemReview\Entities\SystemReview;
use Modules\SystemReview\Entities\SystemReviewType;
;
class SystemReviewController extends Controller
{
              public function __construct()
    {
            //  $this->middleware(['permission:system_reviews_get'])->only('getTypes');
            //  $this->middleware(['permission:system_reviews_add'])->only('addSystemReview');
   
    }
    
    ////user
    public function getTypes(){
       $SystemReviewTypes= SystemReviewType::get();
       return response()->json([
             'status'=>true,
             'code' => 200,
             'message' => 'types for review system',
             'data'=> $SystemReviewTypes
         ]);
    }
    
     public function addSystemReview(AddSystemReviewRequest $request){
         $data=$request->validated();
         $data['user_id']=auth()->guard('api')->user()->id;
        $userSystemReview= SystemReview::where(['user_id'=>$data['user_id']])->first();
        
        if(empty($userSystemReview)){
           $SystemReview= SystemReview::create($data);
           return response()->json([
                 'status'=>true,
                 'code' => 200,
                 'message' => 'review has been added successfully',
                 'data'=> $SystemReview->load(['user','systemReviewType'])
             ]);
            
        }else{
            // return response()->json(['status'=>false,'message'=>'you added a review in prev. time , so cannt add another review'],400);
            return response()->json(['status'=>false,'message'=>'لا تستطيع اضافة تعليق اخر '],400);
        
        }
    }
}
