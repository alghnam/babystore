<?php
namespace Modules\Reward\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\Reward\Repositories\User\RewardRepositoryInterface;

class RewardRepository extends EloquentRepository implements RewardRepositoryInterface
{

    public function getRewards($model,$request,$status){
      $getRewards=  $model->where(['status'=>$status])->paginate($request->total);
      if(count($getRewards)){
          return 'لا يوجد اي مكافات لك الى حد الان ';
      }
      return $getRewards;
    }

}
