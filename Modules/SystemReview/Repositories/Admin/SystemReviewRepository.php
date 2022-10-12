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
    //   dd($modelData);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->with(['systemReviewType','user'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
        return $modelData;
    }
    
}
