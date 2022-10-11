<?php

namespace Modules\Category\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Category\Http\Requests\StoreCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Http\Requests\DeleteCategoryRequest;
use Modules\Auth\Repositories\Role\RoleRepository;
use Modules\Auth\Repositories\Permission\PermissionRepository;
use Modules\Geocode\Repositories\Country\CountryRepository;
use Modules\Geocode\Repositories\City\CityRepository;
use Modules\Geocode\Repositories\Town\TownRepository;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Modules\Auth\Entities\User;
use Illuminate\Support\Facades\Storage;
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
        $mainCategories=$this->categoryRepo->getMainCategoriesPaginate($this->category,$request);
         return response()->json([
             'status'=>true,
             'code' => 200,
             'message' => 'main categories has been getten successfully',
             'data'=> $mainCategories
         ]);
     }
     public function getSubCategories(){
                 $subCategories=$this->categoryRepo->getSubCategories($this->category);
         return response()->json([
             'status'=>true,
             'code' => 200,
             'message' => 'sub categories has been getten successfully',
             'data'=> $subCategories
         ]);
     } 
     public function getSubCategoriesForMainCategoryPaginate(Request $request){
                 $subCategories=$this->categoryRepo->getSubCategoriesForMainCategoryPaginate($this->category,$request);
         
         return response()->json([
             'status'=>true,
             'code' => 200,
             'message' => 'sub categories has been getten successfully',
             'data'=> $subCategories
         ]);
         
         
     }  
          public function getSubCategoriesForSubCategoryPaginate(Request $request,$categoryId){
                 $subCategories=$this->categoryRepo->getSubCategoriesForSubCategoryPaginate($this->subCategory,$request,$categoryId);
          if(is_numeric($subCategories)){
             if($subCategories==400){
                          return response()->json([
             'status'=>false,
             'code' => 400,
             'message' => 'this category_id is main category , so cannt get this sub categry from main category , only , can get it from sub category',
             'data'=> null
         ]);
                
             }
        
         }else{
              return response()->json([
             'status'=>true,
             'code' => 200,
             'message' => 'sub categories has been getten successfully',
             'data'=> $subCategories
         ]);
         }
         
         
     }
     
     public function getSecondSubCategories(Request $request)
{
                     $subCategories=$this->categoryRepo->getSecondSubCategoriesPaginate($this->subCategory,$request);

    return response()->json([
             'status'=>true,
             'code' => 200,
             'message' => 'sub categories has been getten successfully',
             'data'=> $subCategories
         ]);
}     
}
