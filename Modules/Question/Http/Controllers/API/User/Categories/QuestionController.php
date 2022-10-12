<?php

namespace Modules\Question\Http\Controllers\API\User\Categories;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Question\Entities\QuestionCategory;
use Modules\Question\Repositories\User\Categories\QuestionRepository;
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
     * @var QuestionCategory
     */
    protected $questionCategory;
   

    /**
     * QuestionsController constructor.
     *
     * @param QuestionRepository $questions
     */
    public function __construct(BaseRepository $baseRepo, QuestionCategory $questionCategory,QuestionRepository $questionCategoryRepo)
    {
        $this->baseRepo = $baseRepo;
        $this->questionCategory = $questionCategory;
        $this->questionCategoryRepo = $questionCategoryRepo;
    }
  
        public function getAllPaginates(Request $request){
        
         try{
        $questions=$this->questionCategoryRepo->getAllPaginates($this->questionCategory,$request);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$questions],200);

               
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
   
      
    
}
