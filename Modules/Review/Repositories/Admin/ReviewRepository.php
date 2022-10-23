<?php
namespace Modules\Review\Repositories\Admin;

use App\Repositories\EloquentRepository;

use Modules\Review\Repositories\Admin\ReviewRepositoryInterface;
use App\Scopes\ActiveScope;

class ReviewRepository extends EloquentRepository implements ReviewRepositoryInterface
{

    public function getAllPaginates($model,$request){
    $modelData=$model->with(['product','user'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
   
    }
    
    public function getReviewsProduct($model,$request,$productId){
        $modelData=$model->where(['product_id'=>$productId])->with(['product','user'])->paginate($request->total);
       return  $modelData;
    }
         public  function trash($model,$request){
      $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
      $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->with(['product','user'])->paginate($request->total);
        return $modelData;
    }

}
