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
          $mainCategories=$model->with('image')->withoutGlobalScope(ActiveScope::class)->where(['parent_id'=>null])->paginate($request->total);
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
        $modelData=$model->with('image')->withoutGlobalScope(ActiveScope::class)->where('parent_id','!=',null)->with('mainCategory')->paginate($request->total);
          return  $modelData;
    }
    public function getSubCategoriesForMain($model,$categoryId){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->where('parent_id',$categoryId)->with('mainCategory')->get();
          return  $modelData;
    }
  
    public  function trashSub($model,$request){
        
        
       $modelData=$this->findAllItemsOnlyTrashed($model);
                    if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
            }
        return $modelData->with('mainCategory')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
    }
    public function getAllCategoriesPaginate($model,$request){
    $modelData=$model->where('locale',config('app.locale'))->with('mainCategory')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
   
    }
        public function store($request,$model){
        $data=$request->all();
        $data['locale']=config('app.locale');

        
        $enteredData=  Arr::except($data ,['image']);

        $category= $model->create($enteredData);



            if(!empty($data['image'])){
                if($request->hasFile('image')){
                    $file_path_original_image_Category= MediaClass::store($request->file('image'),'sub-categories-images');//store category image
                    $file_path_original_image_Category= str_replace("public/","",$file_path_original_image_Category);

                    $data['image']=$file_path_original_image_Category;
                }else{
                    $data['image']=$category->image;
                }
                $category->image()->create(['url'=>$data['image'],'imageable_id'=>$category->id,'imageable_type'=>'Modules\Category\Entities\Category']);
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
                   $file_path_original= MediaClass::store($request->file('image'),'sub-categories-images');//store category image
                    $file_path_original= str_replace("public/","",$file_path_original);

                   $data['image']=$file_path_original;
    
               }else{
                   $data['image']=$category->image;
               }

             if($category->image){
                 $category->image()->update(['url'=>$data['image'],'imageable_id'=>$category->id,'imageable_type'=>'Modules\Category\Entities\Category']);

             }else{
       
                 $category->image()->create(['url'=>$data['image'],'imageable_id'=>$category->id,'imageable_type'=>'Modules\Category\Entities\Category']);
                                 

             }
         }
                        return $category;


        }    

    }

}
