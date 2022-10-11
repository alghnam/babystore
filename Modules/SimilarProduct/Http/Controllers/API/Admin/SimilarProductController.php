<?php

namespace Modules\SimilarProduct\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
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
        // $this->middleware(['permission:similarProducts_store'])->only('store');
        // $this->middleware(['permission:similarProducts_destroy'])->only('destroy');
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
       $similarProduct= $this->similarProductRepo->storeSimilar($this->similarProduct,$productId,$similarId);
       if(is_string($similarProduct)){
            return response()->json(['status'=>false,'message'=>$similarProduct],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'similar Product has been stored successfully',
            'data'=> $similarProduct
        ]);
    }
    
        /**
     * update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$productId)
    {
       $similarProduct= $this->similarProductRepo->updateSimilar($request,$this->similarProduct,$productId);
       if(is_string($similarProduct)){
            return response()->json(['status'=>false,'message'=>$similarProduct],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'similar Product has been stored successfully',
            'data'=> $similarProduct
        ]);
    }

    /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         $similarProduct=$this->baseRepo->find($id,$this->product);
                 if(is_string($similarProduct)){
            return response()->json(['status'=>false,'message'=>$similarProduct],404);
        }
      
             return response()->json([
                 'status'=>true,
                 'code' => 200,
                 'message' => 'Product has been getten successfully',
                 'data'=> $product
             ]);
         
     }


     public function similarsProduct($productId)
     {
         $similarProduct=$this->similarProductRepo->similarsProduct($productId,$this->similarProduct);
                          if(is_string($similarProduct)){
            return response()->json(['status'=>false,'message'=>$similarProduct],404);
        }
        
             return response()->json([
                 'status'=>true,
                 'code' => 200,
                 'message' => 'Product has been getten successfully',
                 'data'=> $similarProduct
             ]);
         
     }
         
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteSimilarProductRequest $request,$productId,$similarId)
    {
       $similarProduct= $this->similarProductRepo->destroySimilar($this->similarProduct,$productId,$similarId);
       if(is_string($similarProduct)){
            return response()->json(['status'=>false,'message'=>$similarProduct],404);
        }
       
     
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed  successfully',
            'data'=> null
        ]); 
       
    }

}
