<?php

namespace Modules\PushNotification\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\PushNotification\Http\Requests\DeletePushNotificationRequest;
use Modules\PushNotification\Http\Requests\StorePushNotificationRequest;
use Modules\PushNotification\Http\Requests\UpdatePushNotificationRequest;
use Modules\PushNotification\Repositories\Admin\PushNotificationRepository;
use  Modules\PushNotification\Entities\PushNotification;
use Modules\PushNotification\Http\Requests\AddToPushNotificationRequest;

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
        $this->middleware(['permission:push_notifications_read'])->only(['index','getAllPaginates','getAllUsersPushNotificationPaginates']);
        $this->middleware(['permission:push_notifications_trash'])->only('trash');
        $this->middleware(['permission:push_notifications_restore'])->only('restore');
        $this->middleware(['permission:push_notifications_restore-all'])->only('restore-all');
        $this->middleware(['permission:push_notifications_show'])->only('show');
        $this->middleware(['permission:push_notifications_store'])->only('store');
        $this->middleware(['permission:push_notifications_update'])->only('update');
        $this->middleware(['permission:push_notifications_destroy'])->only('destroy');
        $this->middleware(['permission:push_notifications_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->pushNotification = $pushNotification;
        $this->pushNotificationRepo = $pushNotificationRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
          try{
        $pushNotifications=$this->pushNotificationRepo->all($this->pushNotification);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotifications],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllPaginates(Request $request){
        
         try{
        $pushNotifications=$this->pushNotificationRepo->getAllPaginates($this->pushNotification,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotifications],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
    public function getAllUsersPushNotificationPaginates(Request $request,$id){
                 try{
        $usersPushNotification=$this->pushNotificationRepo->getAllUsersPushNotificationPaginates($this->pushNotification,$request,$id);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$usersPushNotification],200);

       
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getPushNotificationsProduct(Request $request,$productId){
        
         try{
        $pushNotifications=$this->pushNotificationRepo->getPushNotificationsProduct($this->pushNotification,$request,$productId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotifications],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
            $pushNotifications=$this->pushNotificationRepo->trash($this->pushNotification,$request);
              if(is_string($pushNotifications)){
                return response()->json(['status'=>false,'message'=>$pushNotifications],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotifications],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePushNotificationRequest $request)
    {
        //  try{
       $pushNotification= $this->pushNotificationRepo->store($request,$this->pushNotification);
                     if(is_string($pushNotification)){
                return response()->json(['status'=>false,'message'=>$pushNotification],500);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotification->load('users')],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
              try{
        $pushNotification=$this->pushNotificationRepo->find($id,$this->pushNotification);
                          if(is_string($pushNotification)){
            return response()->json(['status'=>false,'message'=>$pushNotification],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotification],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

 

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePushNotificationRequest $request,$id)
    {

          try{
       $pushNotification= $this->pushNotificationRepo->update($request,$id,$this->pushNotification);
          if(is_string($pushNotification)){
            return response()->json(['status'=>false,'message'=>$pushNotification],500);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotification],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
          try{
        $pushNotification =  $this->pushNotificationRepo->restore($id,$this->pushNotification);
            if(is_string($pushNotification)){
            return response()->json(['status'=>false,'message'=>$pushNotification],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotification],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
          try{
        $pushNotifications =  $this->pushNotificationRepo->restoreAll($this->pushNotification);
                if(is_string($pushNotifications)){
            return response()->json(['status'=>false,'message'=>$pushNotifications],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotifications],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
        
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeletePushNotificationRequest $request,$id)
    {
          try{
       $pushNotification= $this->pushNotificationRepo->destroy($id,$this->pushNotification);
           if(is_string($pushNotification)){
            return response()->json(['status'=>false,'message'=>$pushNotification],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotification],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeletePushNotificationRequest $request,$id)
    {
          try{
        //to make force destroy for a PushNotification must be this PushNotification  not found in PushNotifications table  , must be found in trash PushNotifications
        $pushNotification=$this->pushNotificationRepo->forceDelete($id,$this->pushNotification);
            if(is_string($pushNotification)){
            return response()->json(['status'=>false,'message'=>$pushNotification],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$pushNotification],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
     

 
}
