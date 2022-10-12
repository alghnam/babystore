<?php

namespace Modules\Reward\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Reward\Entities\Reward;
use Modules\Reward\Repositories\RewardRepository;
use App\Repositories\BaseRepository;

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
        $this->middleware(['permission:rewards_get'])->only('getRewards');
        $this->baseRepo = $baseRepo;
        $this->reward = $reward;
        $this->rewardRepo = $rewardRepo;
    }
    
   
    
    
    ///for user
    
    public function getRewards(Request $request,$status){
        try{
            $rewards=$this->rewardRepo->getRewards($this->reward,$request,$status);
            if(is_string($rewards)){
                return response()->json(['status'=>false,'message'=>$rewards],404);
            }

                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rewards],200);

               
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
}
