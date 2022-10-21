<?php

namespace Modules\Category\Http\Controllers\API\Admin;

use Illuminate\Routing\Controller;
use Modules\Category\Http\Requests\StoreCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Http\Requests\DeleteCategoryRequest;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Modules\Category\Entities\Category;
use Modules\Category\Repositories\Admin\CategoryRepository;
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
      * CategoriesController constructor.
      *
      * @param CategoryRepository $categories
      */
     public function __construct(BaseRepository $baseRepo, Category $category,CategoryRepository $categoryRepo)
     {
    $this->middleware(['permission:categories_read'])->only(['index','getAllPaginates','getMainCategories','mainCategoryByName','getSubCategories','getFirstSubCategoriesPaginate','getAllCategoriesPaginate','getSubCategoriesForMain']);
    $this->middleware(['permission:categories_trash'])->only('trash');
    $this->middleware(['permission:categories_restore'])->only('restore');
    $this->middleware(['permission:categories_restore-all'])->only('restore-all');
    $this->middleware(['permission:categories_show'])->only('show');
    $this->middleware(['permission:categories_store'])->only('store');
    $this->middleware(['permission:categories_update'])->only('update');
    $this->middleware(['permission:categories_destroy'])->only('destroy');
    $this->middleware(['permission:categories_destroy-force'])->only('destroy-force');
         $this->baseRepo = $baseRepo;
         $this->category = $category;
         $this->categoryRepo = $categoryRepo;
     }
     
              public function getAllPaginates(Request $request){
         
          try{
         $categories=$this->categoryRepo->getAllPaginates($this->category,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$categories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }
     public function getMainCategories(){
                 try{
        $mainCategories=$this->categoryRepo->mainCategories($this->category);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$mainCategories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }

     public function mainCategoryByName($subCategoryId){
           try{
          $mainCategoryByName=$this->categoryRepo->mainCategoryByName($this->category,$subCategoryId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$mainCategoryByName],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }


     public function getSubCategories(){
                   try{
                 $subCategories=$this->categoryRepo->getSubCategories($this->category);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }  
     public function getFirstSubCategoriesPaginate(Request $request){
           try{
                 $subCategories=$this->categoryRepo->getFirstSubCategoriesPaginate($this->category,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }
         public function getAllCategoriesPaginate(Request $request){
         
          try{
         $categories=$this->categoryRepo->getAllCategoriesPaginate($this->category,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$categories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }
 
    public function getSubCategoriesForMain($categoryId){
        try{
        $subCategoriesForMain=$this->categoryRepo->getSubCategoriesForMain($this->category,$categoryId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategoriesForMain],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }



    // methods for trash
    public function trash(Request $request){
  try{
        $Categories=$this->categoryRepo->trash($this->category,$request);
          if(is_string($Categories)){
            return response()->json(['status'=>false,'message'=>$Categories],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$Categories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
        public function trashSub(Request $request){
  try{
        $Categories=$this->categoryRepo->trashSub($this->category,$request);
          if(is_string($Categories)){
            return response()->json(['status'=>false,'message'=>$Categories],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$Categories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        //  try{
       $Category= $this->categoryRepo->store($request,$this->category);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$Category->load(['mainCategory','image'])],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $Category=$this->categoryRepo->find($id,$this->category);
           if(is_string($Category)){
            return response()->json(['status'=>false,'message'=>$Category],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$Category->load(['mainCategory','image'])],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

 

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request,$id)
    {
        try{
           $Category= $this->categoryRepo->update($request,$id,$this->category);
                if(is_string($Category)){
                return response()->json(['status'=>false,'message'=>$Category],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$Category->load(['mainCategory','image'])],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
         try{
            $Category =  $this->categoryRepo->restore($id,$this->category);
            if(is_string($Category)){
            return response()->json(['status'=>false,'message'=>$Category],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$Category->load(['mainCategory','image'])],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
        try{
            $Categories =  $this->categoryRepo->restoreAll($this->category);
           if(is_string($Categories)){
            return response()->json(['status'=>false,'message'=>$Categories],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$Categories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
        
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCategoryRequest $request,$id)
    {
        try{
            $Category= $this->categoryRepo->destroy($id,$this->category);
            if(is_string($Category)){
                return response()->json(['status'=>false,'message'=>$Category],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$Category->load(['mainCategory','image'])],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteCategoryRequest $request,$id)
    {
          try{
            //to make force destroy for a Category must be this Category  not found in Categorys table  , must be found in trash Categorys
            $Category=$this->categoryRepo->forceDelete($id,$this->category);
            if(is_string($Category)){
                return response()->json(['status'=>false,'message'=>$Category],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$Category],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    } 
     
 
   
     
}
