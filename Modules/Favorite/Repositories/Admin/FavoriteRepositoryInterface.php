<?php
namespace Modules\Favorite\Repositories\Admin;

interface FavoriteRepositoryInterface
{
   public function getAllFavoritesPaginate($model,$request);
}
