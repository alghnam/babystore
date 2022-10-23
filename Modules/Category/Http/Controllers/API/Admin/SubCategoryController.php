<?php

namespace Modules\Category\Http\Controllers\API\Admin;

use Illuminate\Routing\Controller;
use Modules\Category\Http\Requests\SubCategory\StoreCategoryRequest;
use Modules\Category\Http\Requests\SubCategory\UpdateCategoryRequest;
use Modules\Category\Http\Requests\SubCategory\DeleteCategoryRequest;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Modules\Auth\Entities\User;
use Modules\Category\Entities\SubCategory;
use Modules\Category\Repositories\Admin\SubCategory\CategoryRepository;

class SubCategoryController extends Controller
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
      * @var SubCategory
      */
     protected $subCategory;
    
 
     /**
      * CategoriesController constructor.
      *
      * @param CategoryRepository $categories
      */
     public function __construct(BaseRepository $baseRepo, SubCategory $subCategory,CategoryRepository $subCategoryRepo)
     {
    $this->middleware(['permission:categories_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:categories_trash'])->only('trash');
    $this->middleware(['permission:categories_restore'])->only('restore');
    $this->middleware(['permission:categories_restore-all'])->only('restore-all');
    $this->middleware(['permission:categories_show'])->only('show');
    $this->middleware(['permission:categories_store'])->only('store');
    $this->middleware(['permission:categories_update'])->only('update');
    $this->middleware(['permission:categories_destroy'])->only('destroy');
    $this->middleware(['permission:categories_destroy-force'])->only('destroy-force');
         $this->baseRepo = $baseRepo;
         $this->subCategory = $subCategory;
         $this->subCategoryRepo = $subCategoryRepo;
     }

     public function getAllPaginates(Request $request){
           try{
                 $subCategories=$this->subCategoryRepo->getAllPaginates($this->subCategory,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategories],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }
 
 
  public function getSecondSubCategoriesForSub($categoryId){
        // try{
        $getSecondSubCategoriesForSub=$this->subCategoryRepo->getSecondSubCategoriesForSub($this->subCategory,$categoryId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$getSecondSubCategoriesForSub],200);

        
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
         $category=$this->subCategoryRepo->find($id,$this->subCategory);
          if(is_string($category)){
            return response()->json(['status'=>false,'message'=>$category],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$category],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
            
         
     }
     
    
    // methods for trash
    public function trash(Request $request){
        try{
        $subCategories=$this->subCategoryRepo->trash($this->subCategory,$request);
        if(is_string($subCategories)){
            return response()->json(['status'=>false,'message'=>$subCategories],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategories],200);

        
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
       $subCategory= $this->subCategoryRepo->store($request,$this->subCategory);
    //   dd($subCategory);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategory->load(['category','category.mainCategory','image'])],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
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
            $subCategory= $this->subCategoryRepo->update($request,$id,$this->subCategory);
            if(is_string($subCategory)){
                return response()->json(['status'=>false,'message'=>$subCategory],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategory->load(['category','category.mainCategory','image'])],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
        try{
            $subCategory =  $this->subCategoryRepo->restore($id,$this->subCategory);
            if(is_string($subCategory)){
                return response()->json(['status'=>false,'message'=>$subCategory],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategory->load(['category','category.mainCategory','image'])],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
        try{
            $subCategories =  $this->subCategoryRepo->restoreAll($this->subCategory);
            if(is_string($subCategories)){
                return response()->json(['status'=>false,'message'=>$subCategories],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategories],200);

        
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
    public function destroy(Request $request,$id)
    {
          try{
       $subCategory= $this->subCategoryRepo->destroy($id,$this->subCategory);
        if(is_string($subCategory)){
            return response()->json(['status'=>false,'message'=>$subCategory],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategory->load(['category','category.mainCategory','image'])],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(Request $request,$id)
    {
          try{
        //to make force destroy for a SubCategory must be this SubCategory  not found in SubCategorys table  , must be found in trash SubCategorys
        $subCategory=$this->subCategoryRepo->forceDelete($id,$this->subCategory);
         if(is_string($subCategory)){
            return response()->json(['status'=>false,'message'=>$subCategory],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$subCategory],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    } 
     
 
  
 
     
}
