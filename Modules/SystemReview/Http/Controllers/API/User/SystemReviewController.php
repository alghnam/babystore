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
             $this->middleware(['permission:system_reviews_get'])->only('getTypes');
             $this->middleware(['permission:system_reviews_add'])->only('addSystemReview');
   
    }
    
    ////user
    public function getTypes(){
        try{
                $SystemReviewTypes= SystemReviewType::get();
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$SystemReviewTypes],200);
    }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
     public function addSystemReview(AddSystemReviewRequest $request){
        //  try{
             $data=$request->validated();
             $data['user_id']=auth()->guard('api')->user()->id;
            $userSystemReview= SystemReview::where(['user_id'=>$data['user_id']])->first();
        
            if(is_string($userSystemReview)){
                return response()->json(['status'=>false,'message'=>$userSystemReview],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$userSystemReview],200);
    // }catch(\Exception $ex){
    //         return response()->json(['status'=>false,'message'=>config('constants.error')],500);

    //     } 
    }
}
