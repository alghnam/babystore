<?php
namespace Modules\Coupon\Repositories\Admin;

interface CouponRepositoryInterface
{
   public function getAllPaginates($model,$request);
   public function trash($id,$model);

}
