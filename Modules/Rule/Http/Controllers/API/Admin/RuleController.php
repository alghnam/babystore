<?php

namespace Modules\Rule\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rule\Entities\Rule;
use Modules\Rule\Http\Requests\StoreRuleRequest;
use Modules\Rule\Http\Requests\UpdateRuleRequest;
use Modules\Rule\Http\Requests\DeleteRuleRequest;
use Modules\Rule\Repositories\Admin\RuleRepository;

class RuleController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var RuleRepository
    */
    protected $ruleRepo;
    /**
    * @var Rule
    */
    protected $rule;


    /**
    * RulesController constructor.
    *
    * @param RuleRepository $rules
    */
    public function __construct(BaseRepository $baseRepo, Rule $rule,RuleRepository $ruleRepo)
    {
    $this->middleware(['permission:rules_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:rules_trash'])->only('trash');
    $this->middleware(['permission:rules_restore'])->only('restore');
    $this->middleware(['permission:rules_restore-all'])->only('restore-all');
    $this->middleware(['permission:rules_show'])->only('show');
    $this->middleware(['permission:rules_store'])->only('store');
    $this->middleware(['permission:rules_update'])->only('update');
    $this->middleware(['permission:rules_destroy'])->only('destroy');
    $this->middleware(['permission:rules_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->rule = $rule;
    $this->ruleRepo = $ruleRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
            $rules=$this->ruleRepo->all($this->rule);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rules],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllPaginates(Request $request){
        try{
        $rules=$this->ruleRepo->getAllPaginates($this->rule,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rules],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
            $rules=$this->ruleRepo->trash($this->rule,$request);

            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rules],200);
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
    public function store(StoreRuleRequest $request)
    {
        try{
            $rule=$this->ruleRepo->store($request,$this->rule);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rule],200);
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
            $rule=$this->ruleRepo->find($id,$this->rule);
    
            if(is_string($rule)){
                return response()->json(['status'=>false,'message'=>$rule],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rule],200);
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
    public function update(UpdateRuleRequest $request,$id)
    {
        try{
    $rule= $this->ruleRepo->update($request,$id,$this->rule);
    if(is_string($rule)){
            return response()->json(['status'=>false,'message'=>$rule],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rule],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

    }


    //methods for restoring
    public function restore($id){
        try{
            $rule =  $this->ruleRepo->restore($id,$this->rule);
             if(is_string($rule)){
                    return response()->json(['status'=>false,'message'=>$rule],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rule],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
        try{
            $rules =  $this->ruleRepo->restoreAll($this->rule);
             if(is_string($rules)){
                    return response()->json(['status'=>false,'message'=>$rules],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rules],200);
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
    public function destroy(DeleteRuleRequest $request,$id)
    {
        try{
            $rule= $this->ruleRepo->destroy($id,$this->rule);
             if(is_string($rule)){
                    return response()->json(['status'=>false,'message'=>$rule],404);
                }
  
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rule],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    public function forceDelete(DeleteRuleRequest $request,$id)
    {
        try{
            //to make force destroy for a Rule must be this Rule  not found in Rules table  , must be found in trash Rules
            $rule=$this->ruleRepo->forceDelete($id,$this->rule);
             if(is_string($rule)){
                    return response()->json(['status'=>false,'message'=>$rule],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rule],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
    
   
}
