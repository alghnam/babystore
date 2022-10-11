<?php

namespace Modules\Product\Http\Controllers\API\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;

use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Http\Requests\DeleteProductRequest;
use Modules\Auth\Repositories\Role\RoleRepository;
use Modules\Auth\Repositories\Permission\PermissionRepository;
use Modules\Geocode\Repositories\Country\CountryRepository;
use Modules\Geocode\Repositories\City\CityRepository;
use Modules\Geocode\Repositories\Town\TownRepository;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Modules\Auth\Entities\User;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\Admin\ProductRepository;
use  Modules\ProductAttribute\Entities\ProductArrayAttribute;
class ProductController extends Controller
{
           /**
     * @var BaseRepository
     */
     protected $baseRepo;
     /**
      * @var ProductRepository
      */
     protected $productRepo;
         /**
      * @var Product
      */
     protected $product;
    
 
     /**
      * ProductsController constructor.
      *
      * @param ProductRepository $products
      */
     public function __construct(BaseRepository $baseRepo, Product $product,ProductArrayAttribute $productArrayAttribute,ProductRepository $productRepo)
     {
         $this->middleware(['permission:products_read'])->only(['index','getAllPaginates']);
         $this->middleware(['permission:products_trash'])->only('trash');
         $this->middleware(['permission:products_restore'])->only('restore');
         $this->middleware(['permission:products_restore-all'])->only('restore-all');
         $this->middleware(['permission:products_show'])->only('show');
         $this->middleware(['permission:products_store'])->only('store');
         $this->middleware(['permission:products_update'])->only('update');
         $this->middleware(['permission:products_destroy'])->only('destroy');
         $this->middleware(['permission:products_destroy-force'])->only('destroy-force');
         $this->baseRepo = $baseRepo;
         $this->product = $product;
         $this->productArrayAttribute = $productArrayAttribute;
         $this->productRepo = $productRepo;
     }
     /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function index(){
         
           try{
         $products=$this->productRepo->all($this->product);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$products],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
     }
      public function search($word){
                  $products=$this->productRepo->search($this->product,$word);
         return response()->json([
             'status'=>true,
             'code' => 200,
             'message' => 'Products has been getten successfully',
             'data'=> $products
         ]);
     }
    //  public function someProducts(){
    //               $products=$this->productRepo->someProducts($this->product);
    //      return response()->json([
    //          'status'=>true,
    //          'code' => 200,
    //          'message' => 'Products has been getten successfully',
    //          'data'=> $products
    //      ]);
    //  }
         public function getAllPaginates(Request $request){
         
           try{
         $products=$this->productRepo->getAllPaginates($this->product,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$products],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }
 
    public function getProductsForCategory($categoryId){
          try{
        $getProductsForCategory=$this->productRepo->getProductsForCategory($this->product,$categoryId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$getProductsForCategory],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
    }
    
      public function getProductsForSubCategoryTable($subCategoryId,Request $request){
  try{
        $getProductsForCategory=$this->productRepo->getProductsForSubCategoryTable($this->product,$subCategoryId,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$getProductsForCategory],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
            
         
        
    }

    // public function similarsProduct($id){
    //     $similarsProduct=$this->productRepo->similarsProduct($this->product,$id);
    //     return response()->json([
    //         'status'=>true,
    //         'code' => 200,
    //         'message' => 'similarsProduct  has been getten successfully',
    //         'data'=> $similarsProduct
    //     ]);
    // }


     public function searchForSimilars($word){
          try{
        $search=$this->productRepo->searchForSimilars($this->product,$word);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$search],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

 
     // methods for trash
     public function trash(Request $request){
   try{
         $products=$this->productRepo->trash($this->product,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$products],200);

        
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
     public function store(StoreProductRequest $request)
     {
        //   try{
         $product=$this->productRepo->store($request,$this->product);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
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
         $product=$this->productRepo->find($id,$this->product);
                                  if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
            
         
     }
         
          public function showImagesProduct($id)
     {
          try{
         $product=$this->productRepo->findImagesProduct($id,$this->product);
                                           if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
        
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
             
         
     }
     public function mainCategoryProduct($id){
          try{
       $product=$this->productRepo->mainCategoryProduct($id,$this->product);
         if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
        
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
             
         
     }
     
     public function productAttributes($id){
          try{
         $product=$this->productRepo->productAttributes($id,$this->product);
          if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
           
         
     }
     
          public function productArrayAttributes($id){
          try{
         $productArrayAttributes=$this->productRepo->productArrayAttributes($id,$this->productArrayAttribute);
         if(is_string($productArrayAttributes)){
            return response()->json(['status'=>false,'message'=>$productArrayAttributes],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productArrayAttributes],200);

        
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
     public function update(UpdateProductRequest $request,$id)
     {
          try{
        $product= $this->productRepo->update($request,$id,$this->product);
         if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
         
        
 
     }
    public function deleteImage($id){
          try{
        $product= $this->productRepo->deleteImage($id,$this->product);
         if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
        
    }
     public function inventory(){
          try{
        $productsInInventory= $this->productRepo->productsInInventory($this->product);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productsInInventory],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
       
     }
 
     //methods for restoring
     public function restore($id){
         
          try{
         $product =  $this->productRepo->restore($id,$this->product);
          if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

            
 
     }
     public function restoreAll(){
          try{
         $products =  $this->productRepo->restoreAll($this->product);
          if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$products],200);

        
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
     public function destroy(DeleteProductRequest $request,$id)
     {
          try{
        $product= $this->productRepo->destroy($id,$this->product);
         if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
        
     }
     public function forceDelete(DeleteProductRequest $request,$id)
     {
          try{
         //to make force destroy for a Product must be this Product  not found in Products table  , must be found in trash Products
         $product=$this->productRepo->forceDelete($id,$this->product);
          if(is_string($product)){
            return response()->json(['status'=>false,'message'=>$product],404);
        }
        
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$product],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
     }
}