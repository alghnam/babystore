<?php
namespace Modules\PushNotification\Repositories\Admin;

interface PushNotificationRepositoryInterface
{
   public function getAllPaginates($model,$request);
   public function trash($model,$request);
   public function store($request,$model);
   public function update($request,$id,$model);
   public function forceDelete($id,$model);

}
