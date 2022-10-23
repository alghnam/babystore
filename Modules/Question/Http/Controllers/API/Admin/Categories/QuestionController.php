<?php

namespace Modules\Question\Http\Controllers\API\Admin\Categories;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Question\Entities\QuestionCategory;
use Modules\Question\Http\Requests\Categories\DeleteQuestionRequest;
use Modules\Question\Http\Requests\Categories\StoreQuestionRequest;
use Modules\Question\Http\Requests\Categories\UpdateQuestionRequest;
use Modules\Question\Repositories\Admin\Categories\QuestionRepository;

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
    protected $question;
   

    /**
     * QuestionsController constructor.
     *
     * @param QuestionRepository $questions
     */
    public function __construct(BaseRepository $baseRepo, QuestionCategory $question,QuestionRepository $questionRepo)
    {
        $this->middleware(['permission:question-categories_read'])->only('index');
        $this->middleware(['permission:question-categories_trash'])->only('trash');
        $this->middleware(['permission:question-categories_restore'])->only('restore');
        $this->middleware(['permission:question-categories_restore-all'])->only('restore-all');
        $this->middleware(['permission:question_categories_show'])->only('show');
        $this->middleware(['permission:question-categories_store'])->only('store');
        $this->middleware(['permission:question-categories_update'])->only('update');
        $this->middleware(['permission:question-categories_destroy'])->only('destroy');
        $this->middleware(['permission:question-categories_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->question = $question;
        $this->questionRepo = $questionRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        try{
        $questions=$this->questionRepo->all($this->question);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$questions],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
    }
        public function getAllPaginates(Request $request){
        
         try{
        $questions=$this->questionRepo->getAllPaginates($this->question,$request);
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$questions],200);

               
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
       



    // methods for trash
    public function trash(Request $request){
  try{
        $questions=$this->questionRepo->trash($this->question,$request);
              if(is_string($questions)){
            return response()->json(['status'=>false,'message'=>$questions],404);
        }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$questions],200);

        
       }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

       } 
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionRequest $request)
    {
        try{
       $question= $this->questionRepo->store($request,$this->question);
       if(is_string($question)){
            return response()->json(['status'=>false,'message'=>$question],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$question->load('image')],200);

        
      }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

      } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
              try{
        $question=$this->questionRepo->find($id,$this->question);
            if(is_string($question)){
            return response()->json(['status'=>false,'message'=>$question],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$question->load('image')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

 

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionRequest $request,$id)
    {
          try{
       $question= $this->questionRepo->update($request,$id,$this->question);
            if(is_string($question)){
            return response()->json(['status'=>false,'message'=>$question],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$question->load('image')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
          try{
        $question =  $this->questionRepo->restore($id,$this->question);
            if(is_string($question)){
            return response()->json(['status'=>false,'message'=>$question],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$question],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
          try{
        $questions =  $this->questionRepo->restoreAll($this->question);
             if(is_string($questions)){
            return response()->json(['status'=>false,'message'=>$questions],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$questions],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
        
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteQuestionRequest $request,$id)
    {
          try{
       $question= $this->questionRepo->destroy($id,$this->question);
                          if(is_string($question)){
            return response()->json(['status'=>false,'message'=>$question],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$question],200);

        
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteQuestionRequest $request,$id)
    {
          try{
        //to make force destroy for a Question must be this Question  not found in Questions table  , must be found in trash Questions
        $question=$this->questionRepo->forceDelete($id,$this->question);
                          if(is_string($question)){
            return response()->json(['status'=>false,'message'=>$question],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success')],200);

        
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
}
