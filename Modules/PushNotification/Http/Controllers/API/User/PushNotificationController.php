<?php

namespace Modules\PushNotification\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
//use Modules\PushNotification\Http\Requests\DeletePushNotificationRequest;
use Modules\PushNotification\Repositories\User\PushNotificationRepository;
use  Modules\PushNotification\Entities\PushNotification;

class PushNotificationController extends Controller
{
          /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var PushNotificationRepository
     */
    protected $pushNotificationRepo;
    /**
     * @var PushNotification
     */
    protected $pushNotification;
   

    /**
     * PushNotificationsController constructor.
     *
     * @param PushNotificationRepository $pushNotifications
     */
    public function __construct(BaseRepository $baseRepo, PushNotification $pushNotification,PushNotificationRepository $pushNotificationRepo)
    {
        $this->baseRepo = $baseRepo;
        $this->pushNotification = $pushNotification;
        $this->pushNotificationRepo = $pushNotificationRepo;
    }
    public function getNotificationsForUser(Request $request){
        
          try{
        $pushNotifications=$this->pushNotificationRepo->getNotificationsForUser($this->pushNotification,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotifications],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getLatestNotificationForUser(){
        
         try{
        $pushNotifications=$this->pushNotificationRepo->getLatestNotificationForUser();
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotifications],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    

 
}
