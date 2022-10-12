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
        $modelData=$model->with('users')->paginate($request->total);
          return  $modelData;
    }

    public function getAllUsersPushNotificationPaginates($model,$request,$id){
       $getAllUsersPushNotificationPaginates= $model->where(['id'=>$id])->first();
        return $getAllUsersPushNotificationPaginates->users()->paginate($request->total);

    }

    // methods overrides
    public function store($request,$model){
         $dataa=$request->validated();
         
         $dataa['users']=array_unique($dataa['users']);

         $PushNotification= $model->create($dataa);
        if(!empty($dataa['users'])){
            $PushNotification->users()->attach($dataa['users']);//to create notifications for a user
        }
            //send this notification for users by using push notification
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereIn('id',$dataa['users'])->whereNotNull('fcm_token')->pluck('fcm_token')->all();
          
        $serverKey = 'AAAAaj6h_wU:APA91bG8lot8HO2vMMlXIVGHxswDn9gwbmebfXfh5VbVEuJq52GMYErQZkXWueLHE1Bb3ECIER2m8LcG7rGPnEZigGnGpL6Tt4pQ1VpQYiO1xIXw4y1Gfw66MlE83AsRd_xzhE4Oyfnq';
  
        $data = [
            "registration_ids" => $FcmToken,
            // "registration_ids" => $dataa['users'],
            "notification" => [
                "title" => $request->title,
                "body" => $request->body, 
                "sound" => "default",
                "click_action"=>"Message"
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            return 'حدث خطا ما اثناء الارسال من خلال الفايبربيس';
            // die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response

            
            return $PushNotification;
    }
     
}
