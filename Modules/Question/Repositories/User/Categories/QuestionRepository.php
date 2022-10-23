<?php
namespace Modules\Question\Repositories\User\Categories;

use App\Repositories\EloquentRepository;
use Modules\Question\Repositories\User\Categories\QuestionRepositoryInterface;

class QuestionRepository extends EloquentRepository implements QuestionRepositoryInterface
{
       public function getAllPaginates($model,$request){
        $modelData=$model->with(['image'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
    
}
