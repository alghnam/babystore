<?php
namespace Modules\Question\Repositories\Admin;

use App\Repositories\EloquentRepository;

class QuestionRepository extends EloquentRepository implements QuestionRepositoryInterface
{
   public function getAllPaginates($model,$request){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->with('questionCategory')->paginate($request->total);
          return  $modelData;
    }
    
}
