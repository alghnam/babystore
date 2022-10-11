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
     //   $this->middleware(['permission:rewards_get'])->only('getRewards');
        $this->baseRepo = $baseRepo;
        $this->reward = $reward;
        $this->rewardRepo = $rewardRepo;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('reward::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('reward::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('reward::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('reward::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    
    
    ///for user
    
    public function getRewards(Request $request,$status){
            $rewards=$this->rewardRepo->getRewards($this->reward,$request,$status);
                    if(is_string($rewards)){
            return response()->json(['status'=>false,'message'=>$rewards],404);
        }

     return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'rewards has been getten successfully',
            'data'=> $rewards
        ]);
    
    }
}
