<?php
namespace Modules\Question\Repositories\User;

use App\Repositories\EloquentRepository;

class QuestionRepository extends EloquentRepository implements QuestionRepositoryInterface
{
  public function getAllQuestionsCategoryPaginates($model,$id,$request){
   $question= $this->find($id,$model);
   if(is_string($question)){
       return $question;
   }
   return $question->questionCategory()->paginate($request->total);
  }
    
}
