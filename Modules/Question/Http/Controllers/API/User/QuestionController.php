<?php

namespace Modules\Question\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Question\Entities\Question;
use Modules\Question\Http\Requests\DeleteQuestionRequest;
use Modules\Question\Http\Requests\StoreQuestionRequest;
use Modules\Question\Http\Requests\UpdateQuestionRequest;
use Modules\Question\Repositories\User\QuestionRepository;

class QuestionController extends Controller
{
       /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var QuestionRepository
     */
    protected $questionRepo;
    /**
     * @var Question
     */
    protected $question;
   

    /**
     * QuestionsController constructor.
     *
     * @param QuestionRepository $questions
     */
    public function __construct(BaseRepository $baseRepo, Question $question,QuestionRepository $questionRepo)
    {
        $this->baseRepo = $baseRepo;
        $this->question = $question;
        $this->questionRepo = $questionRepo;
    }
  
        public function getAllPaginates(Request $request){
        
         try{
        $questions=$this->questionRepo->getAllPaginates($this->question,$request);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$questions],200);

               
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
    public function getAllQuestionsCategoryPaginates($categoryId,Request $request){
          try{
        $questions=$this->questionRepo->getAllQuestionsCategoryPaginates($this->question,$categoryId,$request);
        if(is_string($questions)){
            return response()->json(['status'=>true,'message'=>$questions],404);

        }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$questions],200);

               
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
      
    
}
