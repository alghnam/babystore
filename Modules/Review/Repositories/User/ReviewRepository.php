<?php
namespace Modules\Review\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\Review\Repositories\User\ReviewRepositoryInterface;
class ReviewRepository extends EloquentRepository implements ReviewRepositoryInterface
{


    
    ///for user
    public function addReview($request,$model,$productId){
        $data=$request->validated();
        $user=auth()->guard('api')->user();
        if($user==null){
            $review=new $model;
        $review->first_name=$data['first_name'];
        $review->last_name=$data['last_name'];
        $review->description=$data['description'];
        $review->rating=$data['rating'];
        $review->product_id=$productId;
        $review->save();
        }else{
        $review=new $model;
        $review->first_name=$data['first_name'];
        $review->last_name=$data['last_name'];
        $review->description=$data['description'];
        $review->rating=$data['rating'];
        $review->product_id=$productId;
        $review->user_id=$user->id;
        $review->save();
        return $review->load('user.image');
        }
    }

}
