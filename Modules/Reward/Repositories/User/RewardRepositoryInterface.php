<?php
namespace Modules\Reward\Repositories\User;

interface RewardRepositoryInterface
{
   public function getRewards($model,$request,$status);
}
