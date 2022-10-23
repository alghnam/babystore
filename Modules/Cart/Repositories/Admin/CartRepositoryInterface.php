<?php
namespace Modules\Cart\Repositories\Admin;

interface CartRepositoryInterface
{
   public function getAllPaginates($model,$request);
   public function trash($id,$model);

}
