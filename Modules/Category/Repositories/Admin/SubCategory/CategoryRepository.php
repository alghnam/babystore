<?php
namespace Modules\Category\Repositories\Admin\SubCategory;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Auth\Entities\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Category\Repositories\Admin\SubCategory\CategoryRepositoryInterface;
use App\Scopes\ActiveScope;

class CategoryRepository extends EloquentRepository implements CategoryRepositoryInterface
{

 
    public function getAllPaginates($model,$request){
        $modelData=$model->with(['category','category.mainCategory','image'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }


       public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->with(['category','category.mainCategory','image'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
        return $modelData;
    }
    
        public function getSecondSubCategoriesForSub($model,$categoryId){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->where('category_id',$categoryId)->with(['category','category.mainCategory','image'])->get();
        
          return  $modelData;
    }
    // methods overrides
    public function store($request,$model){
        $data=$request->validated();
        $data['locale']=config('app.locale');

        
        $enteredData=  Arr::except($data ,['image']);

        $category= $model->create($enteredData);
// dd($category);

            if(!empty($data['image'])){
                if($request->hasFile('image')){
                    $file_path_original_image_Category= MediaClass::store($request->file('image'),'second-sub-categories-images');//store category image
                    $file_path_original_image_Category= str_replace("public/","",$file_path_original_image_Category);

                    $data['image']=$file_path_original_image_Category;
                }else{
                    $data['image']=$category->image;
                }
                $category->image()->create(['url'=>$data['image'],'imageable_id'=>$category->id,'imageable_type'=>'Modules\Category\Entities\SubCategory']);
            
            }
            return $category;
    }
        public function update($request,$id,$model){

        $category=$this->find($id,$model);
        // dd($category);
        if(!empty($category)){
            
            $data= $request->all();
    
            $enteredData=  Arr::except($data ,['image']);
            $category->update($enteredData);
            
    

         if(!empty($data['image'])){
               if($request->hasFile('image')){
                   $file_path_original= MediaClass::store($request->file('image'),'second-sub-categories-images');//store category image
                    $file_path_original= str_replace("public/","",$file_path_original);

                   $data['image']=$file_path_original;
    
               }else{
                   $data['image']=$category->image;
               }
             if($category->image){
                 $category->image()->update(['url'=>$data['image'],'imageable_id'=>$category->id,'imageable_type'=>'Modules\Category\Entities\SubCategory']);
                //   dd($data['image']);
       
             }else{
       
                 $category->image()->create(['url'=>$data['image'],'imageable_id'=>$category->id,'imageable_type'=>'Modules\Category\Entities\SubCategory']);
             }
             
         }

        }  


        return $category;
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

    
}
