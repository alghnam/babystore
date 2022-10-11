<?php
namespace Modules\SystemReview\Repositories\Admin;

use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;
use Modules\SystemReview\Entities\SystemReview;
use App\Scopes\ActiveScope;

class SystemReviewRepository extends EloquentRepository implements SystemReviewRepositoryInterface
{
  
          public function getAllPaginates($model,$request){
        $modelData=$model->with(['systemReviewType','user'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
       public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
                            return trans('messages.there is not found any items in trash');

        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->with(['systemReviewType','user'])->where('locale',config('app.locale'))->paginate($request->total);
        return $modelData;
    }
    
}
