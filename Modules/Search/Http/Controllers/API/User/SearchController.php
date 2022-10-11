<?php

namespace Modules\Search\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Search\Entities\Search;
use Illuminate\Support\Facades\Storage;


class SearchController extends Controller
{
            public function __construct()
    {
            //  $this->middleware(['permission:searches_last'])->only('latestResultsSearch');
            //  $this->middleware(['permission:searches_delete'])->only('deleteResultSearchUser');
            //  $this->middleware(['permission:searches_delete-all'])->only('deleteAllResultSearchUser');
   
    }
    public function latestResultsSearch(){
            $user=auth()->guard('api')->user();
                // dd($user);
                if($user==null){
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                    }else{
                       $session_id=Storage::get('session_id');

                    }
                    $search=Search::where(['session_id'=>$session_id])->where('status',1)->get();
                    if(empty($search)){
                                    // return response()->json(['status'=>false,'message'=>'results search is empty'],404);
                                    return response()->json(['status'=>false,'message'=>'ما من نتائج بحث'],404);


                    }else{
                        return response()->json([
                            'status'=>true,
                            'message' => 'results search for this user has been getten successfully',
                            'data'=> $search
                        ]);
                    }
                }else{
                                        $search=Search::where(['user_id'=>$user->id])->where('status',1)->paginate(20);
                        return response()->json([
                            'status'=>true,
                            'message' => 'results search for this user has been getten successfully',
                            'data'=> $search
                        ]);                    
                }
        Search::get;
    }
    public function deleteResultSearchUser($id){
       $search= Search::where('id',$id)->first();
        if($search){
            $search->delete();
           return response()->json([
                            'status'=>true,
                            'code' => 200,
                            'message' => 'result search for this user has been deleted successfully',
                            'data'=> null
                        ]);
        }else{
                                                // return response()->json(['status'=>false,'message'=>'results search is empty'],404);
                                    return response()->json(['status'=>false,'message'=>'ما من نتائج بحث'],404);

        }
    }
    
    public function deleteAllResultSearchUser(){
                               $session_id=Storage::get('session_id');

    $user=auth()->guard('api')->user();
    if($user==null){
        
       $allResultSearchUser= Search::where(['session_id'=>$session_id])->get();
    }else{
       $allResultSearchUser= Search::where(['user_id'=>auth()->guard('api')->user()->id])->get();
        
    }
       if(count($allResultSearchUser)!=0){
           
        foreach($allResultSearchUser as $resultSearchUser){
            $resultSearchUser->delete();
        }
        return response()->json([
                            'status'=>true,
                            'code' => 200,
                            'message' => 'all results search for this user has been deleted successfully',
                            'data'=> null
                        ]);
       }else{
                                               // return response()->json(['status'=>false,'message'=>'results search is empty'],404);
                                    return response()->json(['status'=>false,'message'=>'ما من نتائج بحث'],404);

       }
    }
  
}
