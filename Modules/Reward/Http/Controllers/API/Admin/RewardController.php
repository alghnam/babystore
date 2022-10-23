<?php

namespace Modules\Reward\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Reward\Entities\Reward;
use Modules\Reward\Http\Requests\StoreRewardRequest;
use Modules\Reward\Http\Requests\UpdateRewardRequest;
use Modules\Reward\Http\Requests\DeleteRewardRequest;
use Modules\Reward\Repositories\Admin\RewardRepository;

class RewardController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var RewardRepository
    */
    protected $rewardRepo;
    /**
    * @var Reward
    */
    protected $reward;


    /**
    * RewardsController constructor.
    *
    * @param RewardRepository $rewards
    */
    public function __construct(BaseRepository $baseRepo, Reward $reward,RewardRepository $rewardRepo)
    {
    $this->middleware(['permission:rewards_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:rewards_trash'])->only('trash');
    $this->middleware(['permission:rewards_restore'])->only('restore');
    $this->middleware(['permission:rewards_restore-all'])->only('restore-all');
    $this->middleware(['permission:rewards_show'])->only('show');
    $this->middleware(['permission:rewards_store'])->only('store');
    $this->middleware(['permission:rewards_update'])->only('update');
    $this->middleware(['permission:rewards_destroy'])->only('destroy');
    $this->middleware(['permission:rewards_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->reward = $reward;
    $this->rewardRepo = $rewardRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
            $rewards=$this->rewardRepo->all($this->reward);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rewards],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllPaginates(Request $request){
    try{
    $rewards=$this->rewardRepo->getAllPaginates($this->reward,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rewards],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
    $rewards=$this->rewardRepo->trash($this->reward,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rewards],200);
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
    public function store(StoreRewardRequest $request)
    {
        try{
            $reward=$this->rewardRepo->store($request,$this->reward);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reward],200);
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
    $reward=$this->rewardRepo->find($id,$this->reward);
    
        if(is_string($reward)){
            return response()->json(['status'=>false,'message'=>$reward],404);
        }
   
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reward],200);
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
    public function update(UpdateRewardRequest $request,$id)
    {
        try{
    $reward= $this->rewardRepo->update($request,$id,$this->reward);
    if(is_string($reward)){
            return response()->json(['status'=>false,'message'=>$reward],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reward],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

    }


    //methods for restoring
    public function restore($id){
        try{
            $reward =  $this->rewardRepo->restore($id,$this->reward);
             if(is_string($reward)){
                    return response()->json(['status'=>false,'message'=>$reward],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reward],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
        try{
            $rewards =  $this->rewardRepo->restoreAll($this->reward);
             if(is_string($rewards)){
                    return response()->json(['status'=>false,'message'=>$rewards],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rewards],200);
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
    public function destroy(DeleteRewardRequest $request,$id)
    {
        try{
            $reward= $this->rewardRepo->destroy($id,$this->reward);
             if(is_string($reward)){
                    return response()->json(['status'=>false,'message'=>$reward],404);
                }
  
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reward],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function forceDelete(DeleteRewardRequest $request,$id)
    {
        try{
            //to make force destroy for a Reward must be this Reward  not found in Rewards table  , must be found in trash Rewards
            $reward=$this->rewardRepo->forceDelete($id,$this->reward);
             if(is_string($reward)){
                    return response()->json(['status'=>false,'message'=>$reward],404);
                }

            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$reward],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    
    
   
}
