<?php
namespace Modules\Banner\Repositories\User;

interface BannerRepositoryInterface
{
   public function getAllBannersForUserPaginate($model,$request);
}
