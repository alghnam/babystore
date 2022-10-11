<?php
namespace Modules\Banner\Repositories\Admin;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Banner\Entities\Banner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Banner\Repositories\Admin\BannerRepositoryInterface;
use App\Scopes\ActiveScope;

class BannerRepository extends EloquentRepository implements BannerRepositoryInterface
{

 
    public function getAllPaginates($model,$request){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->with('product','image')->paginate($request->total);
          return  $modelData;
    }
    // public  function trash($model,$request){
    //   $modelData=$this->findAllItemsOnlyTrashed($model)->with('product')->paginate($request->total);
    //     return $modelData;
    // }
    // methods overrides
    public function store($request,$model){
        $data=$request->all();
        $data['locale']=Session::get('applocale');

        
        $enteredData=  Arr::except($data ,['image']);

        $Banner= $model->create($enteredData);



            if(!empty($data['image'])){
                if($request->hasFile('image')){
                    $file_path_original_image_Banner= MediaClass::store($request->file('image'),'banner-images');//store Banner image
                    
                                                        $file_path_original_without_public= str_replace("public/","",$file_path_original_image_Banner);

                $data['image']=$file_path_original_without_public;
                }else{
                    $data['image']=$Banner->image;
                }
                $Banner->image()->create(['url'=>$data['image'],'imageable_id'=>$Banner->id,'imageable_type'=>'Modules\Banner\Entities\Banner']);
            }
            return $Banner;
    }
        public function update($request,$id,$model){
        $Banner=$this->find($id,$model);
        if(!empty($Banner)){
            
            $data= $request->all();
    
            $enteredData=  Arr::except($data ,['image']);
            $Banner->update($enteredData);
            
    
    
         if(!empty($data['image'])){
               if($request->hasFile('image')){
                   $file_path_original= MediaClass::store($request->file('image'),'banner-images');//store Banner image
                                                                           $file_path_original_without_public= str_replace("public/","",$file_path_original);

                $data['image']=$file_path_original_without_public;

               }else{
                   $data['image']=$Banner->image;
               }
             if($Banner->image){
                //   dd($data['image']);
                 $Banner->image()->update(['url'=>$data['image'],'imageable_id'=>$Banner->id,'imageable_type'=>'Modules\Banner\Entities\Banner']);
       
             }else{
       
                 $Banner->image()->create(['url'=>$data['image'],'imageable_id'=>$Banner->id,'imageable_type'=>'Modules\Banner\Entities\Banner']);
             }
         }

        }    

        return $Banner;
    }

//     public function forceDelete($id,$model){
//         //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
//         $itemInTableitems = $this->find($id,$model);//find this item from  table items
//         if(empty($itemInTableitems)){//this item not found in items table
//             $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
//             if(empty($itemInTrash)){//this item not found in trash items
//  //   return __('this item  found in system so you cannt   delete it by forcely , you can delete it Temporarily after that delete it by forcely');
            
//             return 'هذا العنصر  غير موجود بسلة المحذوفات لذلك يمكنك حذفه من النظام بالبداية وبعد ذلك حذفه من سلة المحذوفات  ';
            
                
//             }else{
//                  MediaClass::delete($itemInTrash->image);
               
//                 $itemInTrash->forceDelete();
                
//                 return $itemInTrash;
//             }
//         }else{

//             // return __('not found');
//             return 'غير موجود بالنظام ';
                  
//                           }
                          


//     }
    
        public function deleteImage($idImage){
        
       $image= ProductImage::find($idImage);
       if($image){
       $image->delete();
       }else{
           
                        // return __('not found');
            return 'غير موجود بالنظام ';
                
       }
       return $image;
        
    }

    
}
