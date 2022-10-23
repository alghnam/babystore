<?php

namespace Modules\SimilarProduct\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SimilarProduct\Http\Requests\DeleteSimilarProductRequest;
use Modules\SimilarProduct\Http\Requests\StoreSimilarProductRequest;
use Modules\SimilarProduct\Repositories\Admin\SimilarProductRepository;
use Modules\Product\Repositories\Admin\ProductRepository;
use Modules\SimilarProduct\Entities\SimilarProduct;
use Modules\Product\Entities\Product;

class SimilarProductController extends Controller
{
     /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var SimilarProductRepository
     */
    protected $similarProductRepo;
    /**
     * @var ProductRepository
     */
    protected $productRepo;
        /**
     * @var SimilarProduct
     */
    protected $similarProduct;        
    /**
     * @var Product
     */
    protected $Product;
   

    /**
     * ProductsController constructor.
     *
     * @param SimilarProductRepository $similarProducts
     */
    public function __construct(BaseRepository $baseRepo, SimilarProduct $similarProduct,Product $product,SimilarProductRepository $similarProductRepo,ProductRepository $productRepo)
    {
        $this->middleware(['permission:similar_products_read'])->only('similarsProduct');
        $this->middleware(['permission:similar_products_show'])->only('show');
        $this->middleware(['permission:similar_products_store'])->only('store');
        $this->middleware(['permission:similar_products_update'])->only('update');
        $this->middleware(['permission:similar_products_destroy'])->only('destroy');
        $this->baseRepo = $baseRepo;
        $this->similarProduct = $similarProduct;
        $this->product = $product;
        $this->similarProductRepo = $similarProductRepo;
        $this->ProductRepo = $productRepo;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($productId,$similarId)
    {
        try{
           $similarProduct= $this->similarProductRepo->storeSimilar($this->similarProduct,$productId,$similarId);
           if(is_string($similarProduct)){
                return response()->json(['status'=>false,'message'=>$similarProduct],404);
            }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$similarProduct],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
        /**
     * update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$productId)
    {
        try{
       $similarProduct= $this->similarProductRepo->updateSimilar($request,$this->similarProduct,$productId);
       if(is_string($similarProduct)){
            return response()->json(['status'=>false,'message'=>$similarProduct],404);
        }
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$similarProduct],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
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
         $similarProduct=$this->similarProductRepo->find($id,$this->product);
                 if(is_string($similarProduct)){
            return response()->json(['status'=>false,'message'=>$similarProduct],404);
        }
      
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$similarProduct],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
         
     }


     public function similarsProduct($productId)
     {
        //  try{
         $similarProduct=$this->similarProductRepo->similarsProduct($productId,$this->similarProduct);
                          if(is_string($similarProduct)){
            return response()->json(['status'=>false,'message'=>$similarProduct],404);
        }
        
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$similarProduct],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
     }
         
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteSimilarProductRequest $request,$productId,$similarId)
    {
        try{
           $similarProduct= $this->similarProductRepo->destroySimilar($this->similarProduct,$productId,$similarId);
           if(is_string($similarProduct)){
                return response()->json(['status'=>false,'message'=>$similarProduct],404);
            }
           
                  return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$similarProduct],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }

}
