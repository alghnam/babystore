<?php
namespace Modules\Category\Repositories\Admin;

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
use Modules\Category\Repositories\Admin\CategoryRepositoryInterface;
use App\Scopes\ActiveScope;

class CategoryRepository extends EloquentRepository implements CategoryRepositoryInterface
{

        public function mainCategoryByName($model,$subCategoryId){
            $subCategory=$model->find($subCategoryId);
        $mainCategory= $subCategory->mainCategory()->first();
        return $mainCategory;
    }
    public function getAllPaginates($model,$request){
          $mainCategories=$model->withoutGlobalScope(ActiveScope::class)->where(['parent_id'=>null])->paginate($request->total);
        return $mainCategories;
    }   
    public function mainCategories($model){
          $mainCategories=$model->withoutGlobalScope(ActiveScope::class)->where(['parent_id'=>null])->get();
        return $mainCategories;
    }
        public function getSubCategories($model){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->where('parent_id','!=',null)->get();
          return  $modelData;
    }      
    public function getFirstSubCategoriesPaginate($model,$request){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->where('parent_id','!=',null)->with('mainCategory')->paginate($request->total);
          return  $modelData;
    }
    public function getSubCategoriesForMain($model,$categoryId){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->where('parent_id',$categoryId)->with('mainCategory')->get();
          return  $modelData;
    }
  
    public  function trashSub($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model)->with('mainCategory')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
        return $modelData;
    }
    public function getAllCategoriesPaginate($model,$request){
    $modelData=$model->where('locale',config('app.locale'))->with('mainCategory')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
   
    }
    // methods overrides
    public function store($request,$model){
        $data=$request->validated();
        $data['locale']=config('app.locale');

        
        $enteredData=  Arr::except($data ,['image']);

        $category= $model->create($enteredData);



            if(!empty($data['image'])){
                if($request->hasFile('image')){
                    $file_path_original_image_Category= MediaClass::store($request->file('image'),'category-images');//store category image
                    $data['image']=$file_path_original_image_Category;
                }else{
                    $data['image']=$category->image;
                }
                $category->image()->create(['url'=>$data['image'],'imageable_id'=>$category->id,'imageable_type'=>'Modules\Auth\Entities\Category']);
            }
            return $category;
    }
        public function update($request,$id,$model){

        $category=$this->find($id,$model);
        if(!empty($category)){
            
            $data= $request->validated();
    
            $enteredData=  Arr::except($data ,['image']);
            $category->update($enteredData);
            
    
    
         if(!empty($data['image'])){
               if($request->hasFile('image')){
                   $file_path_original= MediaClass::store($request->file('image'),'category-images');//store category image
                   $data['image']=$file_path_original;
    
               }else{
                   $data['image']=$category->image;
               }
             if($category->image){
                //   dd($data['image']);
                 $category->image()->update(['url'=>$data['image'],'imageable_id'=>$category->id,'imageable_type'=>'Modules\Auth\Entities\Category']);
       
             }else{
       
                 $category->image()->create(['url'=>$data['image'],'imageable_id'=>$category->id,'imageable_type'=>'Modules\Auth\Entities\Category']);
             }
         }

        }    

        return $category;
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
            //  MediaClass::delete($itemInTrash->image);

            return 200;
        }

    
}
