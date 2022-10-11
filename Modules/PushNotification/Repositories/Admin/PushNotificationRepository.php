<?php
namespace Modules\PushNotification\Repositories\Admin;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\PushNotification\Entities\PushNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\PushNotification\Repositories\Admin\PushNotificationRepositoryInterface;
use Modules\Auth\Entities\User;
use DB;
use App\Scopes\ActiveScope;

class PushNotificationRepository extends EloquentRepository implements PushNotificationRepositoryInterface
{

 
    public function getAllPaginates($model,$request){
        $modelData=$model->with('users')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }

    public function getAllUsersPushNotificationPaginates($model,$request,$id){
       $getAllUsersPushNotificationPaginates= $model->where(['id'=>$id])->first();
        return $getAllUsersPushNotificationPaginates->users()->paginate($request->total);

    }
    // methods overrides
    public function store($request,$model){
         $dataa=$request->validated();
        // $data['locale']=Session::get('applocale');

         $PushNotification= $model->create($dataa);
        if(!empty($dataa['users'])){
            // foreach($dataa['users'] as $user){
            //     // dd($user);
            //                                   $PushNotificationCount=DB::table('push_notification_user')->where(['user_id'=>$user,'push_notification_id'=>$PushNotification->id])->count();
            //                                 //   dd($PushNotificationCount);
            //         if($PushNotificationCount!==0){
            //             return 'لا يمكنك اضافة نفس المستخدم اكثر من مرة';
            //         }
                
            // }
            $PushNotification->users()->attach($dataa['users']);//to create roles for a user
        }
            //send this notification for users by using push notification
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();
          
        $serverKey = 'BLbQW9sCjVXsC2ixViBwiyh1K45GMsbYYpL5bWdD1KKnx41ub7dxmf2FDlC_PVO3AsdjZiYcMAG9jSHQNHuqQAE';
  
        $data = [
            // "registration_ids" => $FcmToken,
            "registration_ids" => $dataa['users'],
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
      //  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
         dd($result);        
    
            
            return $PushNotification;
    }
     
}
