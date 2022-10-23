<?php
namespace Modules\Movement\Repositories\Admin;

use App\Repositories\EloquentRepository;

use Modules\Movement\Repositories\Admin\MovementRepositoryInterface;
use App\Scopes\ActiveScope;

class MovementRepository extends EloquentRepository implements MovementRepositoryInterface
{
        public function getAllPaginates($model,$request){
        $modelData=$model->with('wallet')->withoutGlobalScope(ActiveScope::class)->latest()->paginate($request->total);
          return  $modelData;
    }
  
 public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';

        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->with(['wallet'])->paginate($request->total);
        return $modelData;
    }
}
