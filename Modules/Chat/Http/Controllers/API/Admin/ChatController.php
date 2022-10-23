<?php

namespace Modules\Chat\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Chat\Http\Requests\DeleteChatRequest;
use Modules\Chat\Http\Requests\StoreChatRequest;
use Modules\Chat\Http\Requests\UpdateChatRequest;
use Modules\Chat\Repositories\Admin\ChatRepository;
use  Modules\Chat\Entities\ChatMessage;
use Modules\Chat\Http\Requests\AddToChatRequest;
use App\Events\NewChatMessage;

class ChatController extends Controller
{
          /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var ChatRepository
     */
    protected $chatRepo;
    /**
     * @var Chat
     */
    protected $chat;
   

    /**
     * ChatsController constructor.
     *
     * @param ChatRepository $chats
     */
    public function __construct(BaseRepository $baseRepo, ChatMessage $chat,ChatRepository $chatRepo)
    {
        $this->middleware(['permission:chats_read'])->only(['index','getAllPaginates']);
        $this->middleware(['permission:chats_trash'])->only('trash');
        $this->middleware(['permission:chats_restore'])->only('restore');
        $this->middleware(['permission:chats_restore-all'])->only('restore-all');
        $this->middleware(['permission:chats_show'])->only('show');
        $this->middleware(['permission:chats_store'])->only('store');
        $this->middleware(['permission:chats_update'])->only('update');
        $this->middleware(['permission:chats_destroy'])->only('destroy');
        $this->middleware(['permission:chats_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->chat = $chat;
        $this->chatRepo = $chatRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
          try{
        $chats=$this->chatRepo->all($this->chat);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chats],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllChatsRecivedPaginates(Request $request){
        
         try{
            $chats=$this->chatRepo->getAllChatsRecivedPaginates($this->chat,$request);
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chats],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function getAllChatsSendedPaginates(Request $request){
        
         try{
            $chats=$this->chatRepo->getAllChatsSendedPaginates($this->chat,$request);
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chats],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
      




    // methods for trash
    public function trashAllChatsSended(Request $request){
  try{
        $chats=$this->chatRepo->trashAllChatsSended($this->chat,$request);
        if(is_string($chats)){
            return response()->json(['status'=>false,'message'=>$chats],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chats],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function trashAllChatsRecieved(Request $request){
        try{
            $chats=$this->chatRepo->trashAllChatsRecieved($this->chat,$request);
             if(is_string($chats)){
            return response()->json(['status'=>false,'message'=>$chats],404);
            }
              return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chats],200);

        
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
    public function store(StoreChatRequest $request)
    {
         try{
            $chat= $this->chatRepo->store($request,$this->chat);
             broadcast(new NewChatMessage($chat))->toOthers();

            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chat->load('client')],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
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
            $chat=$this->chatRepo->find($id,$this->chat);
            if(is_string($chat)){
                return response()->json(['status'=>false,'message'=>$chat],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chat],200);

        
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
    public function update(UpdateChatRequest $request,$id)
    {

          try{
            $chat= $this->chatRepo->update($request,$id,$this->chat);
            if(is_string($chat)){
                return response()->json(['status'=>false,'message'=>$chat],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chat],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
         try{
            $chat =  $this->chatRepo->restore($id,$this->chat);
            if(is_string($chat)){
                return response()->json(['status'=>false,'message'=>$chat],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chat],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAllChatsRecieved(){
        try{
            $chats =  $this->chatRepo->restoreAllChatsRecieved($this->chat);
            if(is_string($chats)){
                return response()->json(['status'=>false,'message'=>$chats],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chats],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
        
    }
        public function restoreAllChatsSended(){
            try{
                $chats =  $this->chatRepo->restoreAllChatsSended($this->chat);
                if(is_string($chats)){
                    return response()->json(['status'=>false,'message'=>$chats],404);
                }
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chats],200);

        
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
    public function destroy(DeleteChatRequest $request,$id)
    {
          try{
            $chat= $this->chatRepo->destroy($id,$this->chat);
            if(is_string($chat)){
                return response()->json(['status'=>false,'message'=>$chat],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chat],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteChatRequest $request,$id)
    {
          try{
            //to make force destroy for a Chat must be this Chat  not found in Chats table  , must be found in trash Chats
            $chat=$this->chatRepo->forceDelete($id,$this->chat);
            if(is_string($chat)){
                return response()->json(['status'=>false,'message'=>$chat],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$chat],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
     


 
}
