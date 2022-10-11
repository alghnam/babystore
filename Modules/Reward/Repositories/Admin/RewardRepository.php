<?php
namespace Modules\Reward\Repositories\Admin;

use App\Repositories\EloquentRepository;

use Modules\Reward\Repositories\Admin\RewardRepositoryInterface;
use App\Scopes\ActiveScope;

class RewardRepository extends EloquentRepository implements RewardRepositoryInterface
{
    public function getAllPaginates($model,$request){
    $modelData=$model->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
   
    }    
   

}
