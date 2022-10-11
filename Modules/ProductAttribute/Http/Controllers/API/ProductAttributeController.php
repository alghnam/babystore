<?php

namespace Modules\ProductAttribute\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\BaseRepository;

use Modules\ProductAttribute\Http\Requests\DeleteProductAttributeRequest;
use Modules\ProductAttribute\Http\Requests\StoreProductAttributeRequest;
use Modules\ProductAttribute\Http\Requests\UpdateProductAttributeRequest;
use Modules\ProductAttribute\Http\Requests\SaveArrayProductAttributesRequest;
use Modules\ProductAttribute\Http\Requests\UpdateArrayProductAttributesRequest;
use Modules\ProductAttribute\Repositories\ProductAttributeRepository;

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
        $this->middleware(['permission:product_attributes_read'])->only('index');
        $this->middleware(['permission:product_attributes_trash'])->only('trash');
        $this->middleware(['permission:product_attributes_restore'])->only('restore');
        $this->middleware(['permission:product_attributes_restore-all'])->only('restore-all');
        $this->middleware(['permission:product_attributes_show'])->only('show');
        $this->middleware(['permission:product_attributes_store'])->only('store');
        $this->middleware(['permission:product_attributes_update'])->only('update');
        $this->middleware(['permission:product_attributes_destroy'])->only('destroy');
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
        
        $productAttributes=$this->productAttributeRepo->all($this->productAttribute);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'ProductAttributes has been getten successfully',
            'data'=> $productAttributes
        ]);
    }
        public function getAllProductAttributesPaginate(Request $request){
        
        $productAttributes=$this->productAttributeRepo->getAllProductAttributesPaginate($this->productAttribute,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'ProductAttributes has been getten successfully(pagination)',
            'data'=> $productAttributes
        ]);
    }
    
    public function getProductAttributesForProduct($productId,Request $request){
        $productAttributesForProduct=$this->productAttributeRepo->getProductAttributesForProduct($this->productAttribute,$request,$productId);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'productAttributes For Product has been getten successfully',
            'data'=> $productAttributesForProduct
        ]);
    }



    // methods for trash
    public function trash(Request $request){
        $productAttributes=$this->productAttributeRepo->trash($this->productAttribute,$request);

        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'ProductAttributes has been getten successfully (in trash)',
            'data'=> $productAttributes
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductAttributeRequest $request)
    {
       $productAttribute= $this->productAttributeRepo->store($request,$this->productAttribute);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'ProductAttribute has been stored successfully',
            'data'=> $productAttribute
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
        $productAttribute=$this->productAttributeRepo->find($id,$this->productAttribute);
        if(empty($productAttribute)){
            return response()->json([
                'status'=>false,
                'code' => 404,
                'message' => 'there is not exit this ProductAttribute',
                'data'=> null
            ]);
        }else{
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'ProductAttribute has been getten successfully',
                'data'=> $productAttribute
            ]);
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
       $productAttribute= $this->productAttributeRepo->update($request,$id,$this->productAttribute);
        if(empty($productAttribute)){
           return response()->json([
               'status'=>false,
               'code' => 404,
               'message' => 'there is not exit this ProductAttribute to update on it',
               'data'=> null
           ]);
       }else{
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'ProductAttribute has been updated successfully',
            'data'=> $productAttribute
        ]);
       }

    }

    //methods for restoring
    public function restore($id){
        
        $productAttribute =  $this->productAttributeRepo->restore($id,$this->productAttribute);
        
        if(empty($productAttribute)){
            return response()->json([
               'status'=>false,
               'code' => 404,
               'message' => 'this ProductAttribute not found in trash to restore it',
               'data'=> null
           ]);
           }else{
               return response()->json([
                   'status'=>true,
                   'code' => 200,
                   'message' => 'ProductAttribute has been restored',
                   'data'=> null
               ]);
           }

    }
    
    public function saveManyAttributes(SaveArrayProductAttributesRequest $request){
                
                
        $productAttributes =  $this->productAttributeRepo->saveManyAttributes($request,$this->productAttribute);
        if(is_numeric($productAttributes)){
            if($productAttributes==400){
                  return response()->json([
                   'status'=>false,
                   'code' => 400,
                   'message' => 'cannt add your request , because you must fill all inputs',
                   'data'=> null
               ]);
            }
        }
            
               return response()->json([
                   'status'=>true,
                   'code' => 200,
                   'message' => 'getting data',
                   'data'=> $productAttributes
               ]);
           
    }
        public function updateManyAttributes($productId,UpdateArrayProductAttributesRequest $request){
                
        $productAttributes =  $this->productAttributeRepo->updateManyAttributes($productId,$request,$this->productArrayAttribute);
        
               return response()->json([
                   'status'=>true,
                   'code' => 200,
                   'message' => 'getting data',
                   'data'=> $productAttributes
               ]);
           
    }
    
    public function saveDetailsArrayAttribute(SaveDetailsArrayProductAttributesRequest $request){
        $productArrayAttributes =  $this->productAttributeRepo->saveDetailsArrayProductAttributes($request,$this->productArrayAttribute);
        
               return response()->json([
                   'status'=>true,
                   'code' => 200,
                   'message' => 'getting data',
                   'data'=> $productArrayAttributes
               ]);
    }  
    public function updateDetailsArrayAttribute($id,UpdateDetailsArrayProductAttributesRequest $request){
        $productArrayAttributes =  $this->productAttributeRepo->updateDetailsArrayProductAttributes($id,$request,$this->productArrayAttribute);
        
               return response()->json([
                   'status'=>true,
                   'code' => 200,
                   'message' => 'getting data',
                   'data'=> $productArrayAttributes
               ]);
    }
            public function deleteManyAttributes($id){
                
          $this->productAttributeRepo->deleteManyAttributes($id,$this->productArrayAttribute);
        
               return response()->json([
                   'status'=>true,
                   'code' => 200,
                   'message' => 'data has been  deleted successfully',
                   'data'=> null
               ]);
           
    }
    
    public function restoreAll(){
        $productAttributes =  $this->productAttributeRepo->restoreAll($this->productAttribute);
        if(empty($productAttributes)){
            return response()->json([
                'status'=>false,
                'code' => 404,
                'message' => 'not found any ProductAttribute in trash to restore all it',
                'data'=> null
            ]);
        }else{
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'restored successfully',
                'data'=> null
            ]);
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
       $productAttribute= $this->productAttributeRepo->destroy($id,$this->productAttribute);
       if(empty($productAttribute)){//this ProductAttribute not found in table ProductAttributes
        return response()->json([
            'status'=>false,
            'code' => 404,
            'message' => 'this ProductAttribute not found in table ProductAttributes',
            'data'=> null
        ]); 
       }else{
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed  successfully',
            'data'=> null
        ]); 
       }
    }
    public function forceDelete(DeleteProductAttributeRequest $request,$id)
    {
        //to make force destroy for a ProductAttribute must be this ProductAttribute  not found in ProductAttributes table  , must be found in trash ProductAttributes
        $productAttribute=$this->productAttributeRepo->forceDelete($id,$this->productAttribute);
        if($productAttribute==404){
            return response()->json([
                'status'=>false,
                'code' => 404,
                'message' => 'this ProductAttribute not found in ProductAttributes table and  trash ProductAttributes to delete it by forcely',
                'data'=> null
            ]);
        }elseif($productAttribute==200){
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'destroyed forcely successfully ',
                'data'=> null
            ]); 
        }elseif($productAttribute==400){
            return response()->json([
                'status'=>false,
                'code' => 400,
                'message' => 'this ProductAttribute  found in ProductAttributes table so you cannt   delete it by forcely , you can delete it Temporarily after that delete it by forcely',
                'data'=> null
            ]);
        }
    }
}
