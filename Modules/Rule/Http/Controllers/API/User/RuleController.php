<?php

namespace Modules\Rule\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rule\Entities\Rule;
use DB;
class RuleController extends Controller
{

    
    public function showRule($id){
        try{
      $rule= Rule::where(['id'=>$id])->first();
    //   dd($rule);
              if(is_string($rule)){
            return response()->json(['status'=>false,'message'=>$rule],404);
        }
       
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rule],200);

               
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
}
