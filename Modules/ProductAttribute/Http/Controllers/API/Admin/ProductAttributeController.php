<?php

namespace Modules\ProductAttribute\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\BaseRepository;

use Modules\ProductAttribute\Http\Requests\DeleteProductAttributeRequest;
use Modules\ProductAttribute\Http\Requests\StoreProductAttributeRequest;
use Modules\ProductAttribute\Http\Requests\UpdateProductAttributeRequest;
use Modules\ProductAttribute\Http\Requests\SaveArrayProductAttributesRequest;
use Modules\ProductAttribute\Http\Requests\UpdateArrayProductAttributesRequest;
use Modules\ProductAttribute\Repositories\Admin\ProductAttributeRepository;

use Modules\ProductAttribute\Entities\ProductAttribute;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;

use Modules\ProductAttribute\Http\Requests\SaveDetailsArrayProductAttributesRequest;
use Modules\ProductAttribute\Http\Requests\UpdateDetailsArrayProductAttributesRequest;


class ProductAttributeController extends Controller
{
         /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var ProductAttributeRepository
     */
    protected $productAttributeRepo;
        /**
     * @var ProductAttribute
     */
    protected $productAttribute;
   

    /**
     * ProductAttributesController constructor.
     *
     * @param ProductAttributeRepository $productAttributes
     */
    public function __construct(BaseRepository $baseRepo, ProductAttribute $productAttribute,ProductArrayAttribute $productArrayAttribute,ProductAttributeRepository $productAttributeRepo)
    {
        $this->middleware(['permission:product_attributes_read'])->only('index','getProductAttributesForProduct');
        $this->middleware(['permission:product_attributes_trash'])->only('trash');
        $this->middleware(['permission:product_attributes_restore'])->only('restore');
        $this->middleware(['permission:product_attributes_restore-all'])->only('restore-all');
        $this->middleware(['permission:product_attributes_show'])->only('show');
        $this->middleware(['permission:product_attributes_store'])->only('store','saveManyAttributes');
        $this->middleware(['permission:product_attributes_update'])->only('update','updateManyAttributes');
        $this->middleware(['permission:product_attributes_destroy'])->only('destroy','deleteManyAttributes');
        $this->middleware(['permission:product_attributes_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->productAttribute = $productAttribute;
        $this->productArrayAttribute = $productArrayAttribute;
        $this->productAttributeRepo = $productAttributeRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        try{
            $productAttributes=$this->productAttributeRepo->all($this->productAttribute);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttributes],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
    }
        public function getAllProductAttributesPaginate(Request $request){
        try{
        $productAttributes=$this->productAttributeRepo->getAllProductAttributesPaginate($this->productAttribute,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttributes],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
    }
    
    public function getProductAttributesForProduct($productId,Request $request){
        try{
        $productAttributesForProduct=$this->productAttributeRepo->getProductAttributesForProduct($this->productAttribute,$request,$productId);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttributes],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
    }



    // methods for trash
    public function trash(Request $request){
        try{
        $productAttributes=$this->productAttributeRepo->trash($this->productAttribute,$request);

            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttributes],200);

        
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
    public function store(StoreProductAttributeRequest $request)
    {
        // try{
       $productAttribute= $this->productAttributeRepo->store($request,$this->productAttribute);
                   if(is_string($productAttribute)){
                return response()->json(['status'=>false,'message'=>$productAttribute],400);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttribute],200);

        
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
            $productAttribute=$this->productAttributeRepo->find($id,$this->productAttribute);
            if(is_string($productAttribute)){
                return response()->json(['status'=>false,'message'=>$productAttribute],404);
            }
    
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttribute],200);

        
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
    public function update(UpdateProductAttributeRequest $request,$id)
    {
        // try{
           $productAttribute= $this->productAttributeRepo->update($request,$id,$this->productAttribute);
            if(is_string($productAttribute)){
                return response()->json(['status'=>false,'message'=>$productAttribute],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttribute],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 

    }

    //methods for restoring
    public function restore($id){
        try{
            $productAttribute =  $this->productAttributeRepo->restore($id,$this->productAttribute);
                if(is_string($productAttribute)){
            return response()->json(['status'=>false,'message'=>$productAttribute],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttribute],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    
    public function saveManyAttributes(SaveArrayProductAttributesRequest $request){
                
        try{
            $productAttributes =  $this->productAttributeRepo->saveManyAttributes($request,$this->productAttribute);
              if(is_string($productAttributes)){
            return response()->json(['status'=>false,'message'=>$productAttributes],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttributes],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
           
    }
        public function updateManyAttributes($productId,UpdateArrayProductAttributesRequest $request){
            try{
                $productAttributes =  $this->productAttributeRepo->updateManyAttributes($productId,$request,$this->productArrayAttribute);
            
                  if(is_string($productAttributes)){
                return response()->json(['status'=>false,'message'=>$productAttributes],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttributes],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
           
    }
    
    public function saveDetailsArrayAttribute(SaveDetailsArrayProductAttributesRequest $request){
        try{
            $productArrayAttributes =  $this->productAttributeRepo->saveDetailsArrayProductAttributes($request,$this->productArrayAttribute);
        
               if(is_string($productArrayAttributes)){
            return response()->json(['status'=>false,'message'=>$productArrayAttributes],400);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productArrayAttributes],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }  
    public function updateDetailsArrayAttribute($id,UpdateDetailsArrayProductAttributesRequest $request){
        // try{
        $productArrayAttributes =  $this->productAttributeRepo->updateDetailsArrayProductAttributes($id,$request,$this->productArrayAttribute);
               if(is_string($productArrayAttributes)){
            return response()->json(['status'=>false,'message'=>$productArrayAttributes],400);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productArrayAttributes->load('image')],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }
            public function deleteManyAttributes($id){
                try{
                
          $this->productAttributeRepo->deleteManyAttributes($id,$this->productArrayAttribute);
        
              
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>null],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
           
    }
    
    public function restoreAll(){
        try{
        $productAttributes =  $this->productAttributeRepo->restoreAll($this->productAttribute);
               if(is_string($productAttributes)){
            return response()->json(['status'=>false,'message'=>$productAttributes],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttributes],200);

        
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
    public function destroy(DeleteProductAttributeRequest $request,$id)
    {
        try{
                //   $productAttribute= $this->productAttributeRepo->destroyAttr($productId,$name,$attrs,$this->productAttribute);

       $productAttribute= $this->productAttributeRepo->destroy($id,$this->productAttribute);
               if(is_string($productAttribute)){
            return response()->json(['status'=>false,'message'=>$productAttribute],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttribute],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function forceDelete(DeleteProductAttributeRequest $request,$id)
    {
        try{
        //to make force destroy for a ProductAttribute must be this ProductAttribute  not found in ProductAttributes table  , must be found in trash ProductAttributes
        $productAttribute=$this->productAttributeRepo->forceDelete($id,$this->productAttribute);
                      if(is_string($productAttribute)){
            return response()->json(['status'=>false,'message'=>$productAttribute],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$productAttribute],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
}
