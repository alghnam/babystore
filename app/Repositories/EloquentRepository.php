<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Scopes\ActiveScope;

class EloquentRepository
{
              public function getAllPaginates($model,$request){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
    public function all($model){
  //  $modelData=$model->where('locale',config('app.locale'))->where('status',1)->get();
    $modelData=$model->get();
       return  $modelData;
   }
   public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
    //   dd($modelData);
        if(is_string($modelData)){
                            return trans('messages.there is not found any items in trash');

        }
    //   $modelData=$this->findAllItemsOnlyTrashed($model)->where('locale',config('app.locale'))->paginate($request->total);
       $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
        return $modelData;
    }
    public function find($id,$model){
        // $item=$model->withoutGlobalScope(ActiveScope::class)->withTrashed()->where('id',$id)->first();
        $item=$model->withoutGlobalScope(ActiveScope::class)->where('id',$id)->first();
            //  dd($item);
                if(empty($item)){
            return trans('messages.this item not found in system');

        }
        return $item;
    }
    public function findItemOnlyTrashed($id,$model){     
        // dd(6);
        $itemInTrash=$model->onlyTrashed()->where('id',$id)->first();//item in trash
        // dd($itemInTrash);
        if(empty($itemInTrash)){
                return trans('messages.this item not exist in trash');
        }else{
            $item=$model->onlyTrashed()->findOrFail($id);//find this item from trash
            return $item;
       }
    }
    public function findAllItemsOnlyTrashed($model){      
        $itemsInTrash=$model->onlyTrashed()->get();//items in trash
        // dd($itemsInTrash);
       if(count($itemsInTrash)==0){
                return trans('messages.there is not found any items in trash');
       }else{

           $items=$model->onlyTrashed();//get items from trash
        // dd($items);
           return $items;
       }
    }
    public function store($request,$model){
        $data=$request->validated();
        $data['locale']=config('app.locale');
        $item= $model->create($data);
        return $item;
    }
    public function update($request,$id,$model){
                $data=$request->validated();

        $item=$this->find($id,$model);
        if(is_string($item)){
            return $item;

        }
        $item->update($data);
        return $item;
    }
    //methods for restoring
    public function restore($id,$model){
        $item = $this->findItemOnlyTrashed($id,$model);//get this item from trash 
                // dd($item);

        if(is_string($item)){
            return $item;
        }else{
            
        if(!empty($item)){//this item not found in trash to restore it
            $item->restore();
        }
        return $item;
        }
    }
    public function restoreAll($model){
        $items = $this->findAllItemsOnlyTrashed($model);//get  items  from trash
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
    //methods for deleting
    public function destroy($id,$item){
        $item=$this->find($id,$item);
        if(is_string($item)){
            return $item;
        }
        //this item not found in table items
        if($item->deleted_at!==null){

            return trans('messages.this item already deleted permenetly');
        }
        $item->delete($item);
        
        return $item;
    }

    public function forceDelete($id,$model){
        //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
        $itemInTableitems = $this->find($id,$model);//find this item from  table items
        if(is_string($itemInTableitems)){//this item not found in items table
            return $itemInTableitems;
        }
        
        $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
        if(is_string($itemInTrash)){//this item not found in trash items table
            return trans('messages.this item not found in trash');
        }
            $itemInTrash->forceDelete();
            return 200;
    }

    public function putStorage(){
        $user=auth()->user();
        Storage::put($user->id.'-orderId',null);
        Storage::put($user->id.'-phone_no_address',null);
    }

}
