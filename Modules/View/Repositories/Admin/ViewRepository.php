<?php
namespace Modules\View\Repositories\Admin;

use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;
use Modules\View\Entities\View;
use App\Scopes\ActiveScope;

class ViewRepository extends EloquentRepository implements ViewRepositoryInterface
{
          public function getAllPaginates($model,$request){
        $modelData=$model->with(['user','product'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
       public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){

                            return 'لا يوجد اي عنصر في سلة المحذوفات';

        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->with(['user','product'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
        return $modelData;
    }
  

}
