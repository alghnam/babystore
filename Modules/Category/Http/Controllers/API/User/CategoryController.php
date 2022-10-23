<?php

namespace Modules\Category\Http\Controllers\API\User;

use Illuminate\Routing\Controller;
use Modules\Category\Http\Requests\StoreCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Http\Requests\DeleteCategoryRequest;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\SubCategory;
use Modules\Category\Repositories\User\CategoryRepository;
class CategoryController extends Controller
{
       /**
     * @var BaseRepository
     */
     protected $baseRepo;
     /**
      * @var CategoryRepository
      */
     protected $categoryRepo;
         /**
      * @var Category
      */
     protected $category;
              /**
      * @var SubCategory
      */
     protected $subCategory;
    
 
     /**
      * CategoriesController constructor.
      *
      * @param CategoryRepository $categories
      */
     public function __construct(BaseRepository $baseRepo, Category $category,SubCategory $subCategory,CategoryRepository $categoryRepo)
     {

         $this->baseRepo = $baseRepo;
         $this->category = $category;
         $this->subCategory = $subCategory;
         $this->categoryRepo = $categoryRepo;
     }
     public function getMainCategoriesPaginate(Request $request){
         try{
        $mainCategories=$this->categoryRepo->getMainCategoriesPaginate($this->category,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$mainCategories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }
     public function getSubCategories(Request $request){
         try{
                 $subCategories=$this->categoryRepo->getSubCategories($this->category,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     } 
     public function getSubCategoriesForMainCategoryPaginate(Request $request){
        //  try{
                 $subCategories=$this->categoryRepo->getSubCategoriesForMainCategoryPaginate($this->category,$request);
         
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategories],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
         
         
     }  
          public function getSubCategoriesForSubCategoryPaginate(Request $request,$categoryId){
              try{
                 $subCategories=$this->categoryRepo->getSubCategoriesForSubCategoryPaginate($this->subCategory,$request,$categoryId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
         
         
     }
     
     public function getSecondSubCategories(Request $request)
{
    try{
                     $subCategories=$this->categoryRepo->getSecondSubCategoriesPaginate($this->subCategory,$request);

           return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
}     
}
