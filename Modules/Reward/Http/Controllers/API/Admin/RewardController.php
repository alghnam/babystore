<?php

namespace Modules\Reward\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
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
    
    $rewards=$this->rewardRepo->all($this->reward);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Rewards has been getten successfully',
        'data'=> $rewards
    ]);
    }

    public function getAllPaginates(Request $request){
    
    $rewards=$this->rewardRepo->getAllPaginates($this->reward,$request);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Rewards has been getten successfully(pagination)',
        'data'=> $rewards
    ]);
    }




    // methods for trash
    public function trash(Request $request){
    $rewards=$this->rewardRepo->trash($this->reward,$request);

    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Rewards has been getten successfully (in trash)',
        'data'=> $rewards
    ]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreRewardRequest $request)
    {
    $reward=$this->rewardRepo->store($request,$this->reward);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Reward has been stored successfully',
        'data'=> $reward
    ]);
    }
    

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
    $reward=$this->rewardRepo->find($id,$this->reward);
    
        if(is_string($reward)){
            return response()->json(['status'=>false,'message'=>$reward],404);
        }
   
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'Reward has been getten successfully',
            'data'=> $reward
        ]);
    
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
    $reward= $this->rewardRepo->update($request,$id,$this->reward);
    if(is_string($reward)){
            return response()->json(['status'=>false,'message'=>$reward],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Reward has been updated successfully',
        'data'=> $reward
    ]);
    

    }

    public function inventory(){
    $rewardsInInventory= $this->rewardRepo->rewardsInInventory($this->reward);
    if(empty($rewardsInInventory)){
    if(is_string($reward)){
            return response()->json(['status'=>false,'message'=>$reward],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'RewardsInInventory getting successfully',
        'data'=> $rewardsInInventory
    ]);
     
    }
    }

    //methods for restoring
    public function restore($id){
    
    $reward =  $this->rewardRepo->restore($id,$this->reward);
     if(is_string($reward)){
            return response()->json(['status'=>false,'message'=>$reward],404);
        }
    
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'Reward has been restored',
                'data'=> $reward
            ]);
        

    }
    public function restoreAll(){
    $rewards =  $this->rewardRepo->restoreAll($this->reward);
     if(is_string($rewards)){
            return response()->json(['status'=>false,'message'=>$rewards],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'restored successfully',
            'data'=> $rewards
        ]);
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteRewardRequest $request,$id)
    {
    $reward= $this->rewardRepo->destroy($id,$this->reward);
     if(is_string($reward)){
            return response()->json(['status'=>false,'message'=>$reward],404);
        }
  
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'destroyed  successfully',
        'data'=> $reward
    ]); 
    
    }
    public function forceDelete(DeleteRewardRequest $request,$id)
    {
    //to make force destroy for a Reward must be this Reward  not found in Rewards table  , must be found in trash Rewards
    $reward=$this->rewardRepo->forceDelete($id,$this->reward);
     if(is_string($reward)){
            return response()->json(['status'=>false,'message'=>$reward],404);
        }

        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed forcely successfully ',
            'data'=> null
        ]); 
    
    }
    
    
   
}
