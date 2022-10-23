<?php
namespace Modules\PushNotification\Repositories\User;

use Modules\PushNotification\Repositories\Admin\PushNotificationRepositoryInterface;
use Modules\Auth\Entities\User;
use DB;
use App\Repositories\EloquentRepository;
class PushNotificationRepository extends EloquentRepository implements PushNotificationRepositoryInterface
{

 
    public function getNotificationsForUser($model,$request){
                $userAuth=auth()->guard('api')->user();
        $user=User::find($userAuth->id);
        if(!empty($user)){
            $modelData= $user->pushNotifications()->latest()->paginate($request->total);
        }
          return  $modelData;
    }

    public function getLatestNotificationForUser(){
                $userAuth=auth()->guard('api')->user();
        $user=User::find($userAuth->id);
        if(!empty($user)){
            $modelData= $user->pushNotifications()->latest()->first();
        }
          return  $modelData;

    }

}
