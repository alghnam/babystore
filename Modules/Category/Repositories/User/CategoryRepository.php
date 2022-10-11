<?php
namespace Modules\Category\Repositories\User;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Category\Entities\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Category\Repositories\User\CategoryRepositoryInterface;
class CategoryRepository extends EloquentRepository implements CategoryRepositoryInterface
{


    public function getMainCategoriesPaginate($model,$request){
          $mainCategories=$model->where(['parent_id'=>null])->paginate($request->total);
        return $mainCategories;
    }   

        public function getSubCategoriesForMainCategoryPaginate($model,$request){
        $modelData=$model->where('parent_id','!=',null)->with(['image','products','products.productImages'])->paginate($request->total);
          return  $modelData;
    }     
            public function getSubCategoriesForSubCategoryPaginate($model,$request,$categoryId){
              $category=  Category::where('id',$categoryId)->first();
              if($category->parnet_id=="null"){
                  return 400; //this category_id is main category , so cannt get this sub categrories from main category , only , can get it from sub category
              }
        $modelData=$model->where('category_id',$categoryId)->with(['image','subCategory','products','products.productImages'])->paginate($request->total);
          return  $modelData;
    }     
    
            public function getSubCategories($model){
        $modelData=$model->where('parent_id','!=',null)->get();
          return  $modelData;
    }  
    
    public function getSecondSubCategoriesPaginate($model,$request){
                $modelData=$model->with(['image'])->paginate($request->total);
          return  $modelData;
    }
}
