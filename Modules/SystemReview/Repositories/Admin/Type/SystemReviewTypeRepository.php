<?php
namespace Modules\SystemReview\Repositories\Admin\Type;

use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;
use Modules\SystemReview\Entities\SystemReviewType;
use App\Scopes\ActiveScope;

class SystemReviewTypeRepository extends EloquentRepository implements SystemReviewTypeRepositoryInterface
{
  
          public function getAllPaginates($model,$request){
        $modelData=$model->with(['systemReviews'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
       public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
                            return trans('messages.there is not found any items in trash');

        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->with(['systemReviews'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
        return $modelData;
    }
    
}
