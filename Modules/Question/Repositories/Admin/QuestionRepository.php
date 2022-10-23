<?php
namespace Modules\Question\Repositories\Admin;

use App\Repositories\EloquentRepository;
use App\Scopes\ActiveScope;

class QuestionRepository extends EloquentRepository implements QuestionRepositoryInterface
{
              public function getAllPaginates($model,$request){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->with('questionCategory')->paginate($request->total);
          return  $modelData;
    }
     public  function trash($model,$request){
      $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
      $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->with('questionCategory')->paginate($request->total);
        return $modelData;
    }
}
