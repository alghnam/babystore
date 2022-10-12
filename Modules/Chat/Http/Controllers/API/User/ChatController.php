<?php

namespace Modules\Chat\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Events\NewChatMessage;

use Modules\Chat\Entities\ChatRoom;
use Modules\Chat\Entities\ChatMessage;
use Modules\Chat\Http\Requests\NewMessageRequest;
class ChatController extends Controller
{
    /**
      * ChatsController constructor.
      *
      */
     public function __construct()
     {
        $this->middleware(['permission:rooms_get'])->only('rooms');
        $this->middleware(['permission:chats_get'])->only('messages');
        $this->middleware(['permission:chats_add'])->only('newMessage');
    
     }
    public function rooms(Request $request){
        try{
            $rooms= ChatRoom::all();
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$rooms],200);
            
                
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    
    public function messages($roomId){
        try{
            $messages= ChatMessage::where('chat_room_id',$roomId)
                ->with(['user','room'])
                ->orderBy('created_at','desc')->with(['user','client'])->get();
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$messages],200);
                            
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function newMessage(NewMessageRequest $request,$roomId){//sender:user(user_id),reciever:superadmin(client_id)
        // try{
            //this meth when user send to admin , but when admin send to user : user_id(sender):superadmin, client_id(reciever):user
            $data=$request->validated();
            $newMessage=new ChatMessage();
            $newMessage->user_id=auth()->guard('api')->user()->id;//sender:user(user_id)
            $newMessage->client_id=1;//reciever:superadmin(client_id)
            $newMessage->chat_room_id=$roomId;
            $newMessage->message=$data['message'];
            $newMessage->save();
            // return $newMessage;
           // return broadcast(new NewChatMessage($newMessage))->toOthers();
            //this line to make real time in sending msgs , which is this new msg when sent , will go into others in real time
          //يعني كل اللي بالقناة بيسمعو لاي رسالة حتيجي عليها ليسمعوها وتيجي عليهم كلهم بان واحد اي تيجي بنفس وقت الارسال توصل للمستقبل هيك باختصار 
          
            broadcast(new NewChatMessage($newMessage))->toOthers();
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$newMessage->load(['user','client'])],200);
        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }
}
