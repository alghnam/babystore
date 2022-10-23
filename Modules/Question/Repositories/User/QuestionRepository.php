<?php
namespace Modules\Question\Repositories\User;

use App\Repositories\EloquentRepository;
use Modules\Question\Entities\QuestionCategory;
use Modules\Question\Entities\Question;

class QuestionRepository extends EloquentRepository implements QuestionRepositoryInterface
{
  public function getAllQuestionsCategoryPaginates($model,$id,$request){
     $QuestionCategory= QuestionCategory::find($id);
     if($QuestionCategory){
        return $QuestionCategory->questions()->paginate($request->total);

     }

  }
    
}
