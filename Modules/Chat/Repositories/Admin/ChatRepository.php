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

 
    public function getAllChatsRecivedPaginates($model,$request){
        $modelData=$model->where('user_id','!=',1)->with(['user','client'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
    
        public function getAllChatsSendedPaginates($model,$request){
        $modelData=$model->where(['user_id'=>1])->with(['user','client'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
    
    public  function trashAllChatsRecieved($model,$request){
      $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
        return $modelData->where('user_id','!=',1)->with(['user','client'])->paginate($request->total);
    }
        public  function trashAllChatsSended($model,$request){
      $modelData=$this->findAllItemsOnlyTrashed($model);
              if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
        return $modelData->where(['user_id'=>1])->with(['user','client'])->paginate($request->total);
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
            }
        }
        
        
    }
    
}
