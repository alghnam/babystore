<?php
namespace Modules\Coupon\Repositories\Admin;

interface CouponRepositoryInterface
{
   public function getAllCouponsPaginate($model,$request);
   public function trash($id,$model);

}
