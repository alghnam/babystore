<?php
namespace Modules\Chat\Repositories\Admin;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Chat\Entities\Chat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Chat\Repositories\Admin\ChatRepositoryInterface;
use App\Scopes\ActiveScope;

class ChatRepository extends EloquentRepository implements ChatRepositoryInterface
{

 
    public function getAllPaginates($model,$request){
        $modelData=$model->where('user_id','!=',1)->with(['user','client'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
    
        public function getAllChatsSendedPaginate($model,$request){
        $modelData=$model->where(['user_id'=>1])->with(['user','client'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
    
    public  function trashAllChatsRecieved($model,$request){
      $modelData=$this->findAllItemsOnlyTrashed($model)->where('user_id','!=',1)->with(['user','client'])->paginate($request->total);
        return $modelData;
    }
        public  function trashAllChatsSended($model,$request){
      $modelData=$this->findAllItemsOnlyTrashed($model)->where(['user_id'=>1])->with(['user','client'])->paginate($request->total);
        return $modelData;
    }


  public function findAllItemsOnlyTrashedSended($model){      
        $itemsInTrash=$model->onlyTrashed()->withoutGlobalScope(ActiveScope::class)->where(['user_id'=>1])->get();//items in trash
       if(count($itemsInTrash)==0){
                return trans('messages.there is not found any items in trash');
       }else{

           $items=$model->onlyTrashed();//get items from trash
           return $items;
       }
    }
  public function findAllItemsOnlyTrashedRecieved($model){      
        $itemsInTrash=$model->onlyTrashed()->withoutGlobalScope(ActiveScope::class)->where('user_id','!=',1)->get();//items in trash
       if(count($itemsInTrash)==0){
                return trans('messages.there is not found any items in trash');
       }else{

           $items=$model->onlyTrashed();//get items from trash
           return $items;
       }
    }
    
public function restoreAllChatsRecieved($model){
        $items = $this->findAllItemsOnlyTrashedRecieved($model);//get  items  from trash
        if(is_string($items)){
            
            return $items;
        }else{
            if(!empty($items)){//there is not found any item in trash
                $items = $items->restore();//restore all items from trash into items table
                return $items;
                return 'تمت الاستعادة بنجاح';
                // return trans('messages.items has been restored successfully');
            }
        }
        
        
    }
    
    public function restoreAllChatsSended($model){
        $items = $this->findAllItemsOnlyTrashedSended($model);//get  items  from trash
        if(is_string($items)){
            
            return $items;
        }else{
            if(!empty($items)){//there is not found any item in trash
                $items = $items->restore();//restore all items from trash into items table
                return $items;
                return 'تمت الاستعادة بنجاح';
                // return trans('messages.items has been restored successfully');
            }
        }
        
        
    }
    
}
