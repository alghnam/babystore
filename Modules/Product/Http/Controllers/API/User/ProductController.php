<?php

namespace Modules\Product\Http\Controllers\API\User;

use Illuminate\Routing\Controller;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\User\ProductRepository;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use AmrShawky\LaravelCurrency\Facade\Currency;


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
      * @var ProductArrayAttribute
      */
     protected $Product_array_attribute;
     
     
    
 
     /**
      * ProductsController constructor.
      *
      * @param ProductRepository $products
      */
     public function __construct(BaseRepository $baseRepo, Product $product,ProductRepository $productRepo,ProductArrayAttribute $product_array_attribute)
     {



         $this->baseRepo = $baseRepo;
         $this->product = $product;
         $this->product_array_attribute = $product_array_attribute;
         $this->productRepo = $productRepo;
     }
    public function getLocation(){
        try{
            $data=$this->productRepo->getLocation($this->product);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }


     public function getMoreSaleProducts(Request $request){
         try{
        $data=$this->productRepo->getMoreSaleProducts($this->product,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function getModernProducts(Request $request){
        try{
        $data=$this->productRepo->getModernProducts($this->product,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function getOffersProducts(Request $request){
        // try{
        $data=$this->productRepo->getOffersProducts($this->product,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }
    
     public function getMoreSaleProductsHome(Request $request){
         try{
        $getMoreSaleProducts=$this->productRepo->getMoreSaleProducts($this->product,$request);
        return $getMoreSaleProducts;

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function getModernProductsHome(Request $request){
        try{
        $getModernProducts=$this->productRepo->getModernProducts($this->product,$request);

        return $getModernProducts;

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function getOffersProductsHome(Request $request){
        try{
        $getOffersProducts=$this->productRepo->getOffersProducts($this->product,$request);

        return $getOffersProducts;

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
    
    public function getAllDataProductsInHome(Request $request){
        try{
        $getMoreSaleProducts=$this->getMoreSaleProductsHome($request);
        $getModernProducts=$this->getModernProductsHome($request);
        $getOffersProducts=$this->getOffersProductsHome($request);
        $data=[
            'getMoreSaleProducts'=>$getMoreSaleProducts,
            'getModernProducts'=>$getModernProducts,
            'getOffersProducts'=>$getOffersProducts
            ];
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function getProductsForCategory($categoryId,Request $request){
        try{
        $data=$this->productRepo->getProductsForCategory($this->product,$categoryId,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getProductsForSubCategoryTable($subCategoryId,Request $request){
            try{
        $data=$this->productRepo->getProductsForSubCategoryTable($this->product,$subCategoryId,$request);
                
            if(is_string($data)){
                return response()->json(['status'=>false,'message'=>$data],404);
            }
       
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    public function search($word,Request $request){
     //   try{
        $data=$this->productRepo->search($this->product,$word,$request);
       
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    }

    public function searchMoreSale($word,Request $request){
        try{
        $data=$this->productRepo->searchMoreSale($this->product,$word,$request);
       
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function searchModern($word,Request $request){
        try{
        $data=$this->productRepo->searchModern($this->product,$word,$request);
       
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function searchOffers($word,Request $request){
        try{
        $data=$this->productRepo->searchOffers($this->product,$word,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function searchProductsSpesificPriceSpesificWord($word,$price1,$price2,Request $request){
        try{
        $data=$this->productRepo->searchProductsSpesificPriceSpesificWord($this->product,$word,$price1,$price2,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function searchPriceMoreSale($word,$price1,$price2,Request $request){
        try{
        $data=$this->productRepo->searchPriceMoreSale($this->product,$word,$price1,$price2,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    public function searchPriceOffers($word,$price1,$price2,Request $request){
        try{
        $data=$this->productRepo->searchPriceOffers($this->product,$word,$price1,$price2,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function searchProductsSpesificCategorySpesificWord($categoryId,$word,Request $request){
        try{
                $data=$this->productRepo->searchProductsSpesificCategorySpesificWord($this->product,$categoryId,$word,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function searchProductsSpesificCategorySpesificWordOrderMoreSale($word,Request $request){
        try{
        $data=$this->productRepo->searchProductsSpesificCategorySpesificWordOrderMoreSale($this->product,$word,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function searchPriceModern($word,$price1,$price2,Request $request){
        try{
        $data=$this->productRepo->searchPriceModern($this->product,$word,$price1,$price2,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function searchProductsSpesificCategorySpesificWordOrderOffers($word,Request $request){
        try{
        $data=$this->productRepo->searchProductsSpesificCategorySpesificWordOrderOffers($this->product,$word,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function searchProductsSpesificCategoryAndPriceSpesificWord($word,Request $request){
        try{
        $data=$this->productRepo->searchProductsSpesificCategoryAndPriceSpesificWord($this->product,$word,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function showProductWithRelations($id){
        // try{
        $showProductWithRelations=$this->productRepo->showProductWithRelations($this->product,$id);
        if(empty($showProductWithRelations)){
                        return response()->json(['status'=>false,'message'=>'غير موجود'],404);

        }
        $showProductWithRelations->currency_country=$this->baseRepo->countryCurrency();

            $data=[
                    'currency_country'=>$showProductWithRelations->currency_country,
                'product_details'=>$showProductWithRelations
                ];
           
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
        
    }

public function showAttributesProduct($id){
    try{
        $data=$this->productRepo->showAttributesProduct($this->product,$id);
                        if(is_string($data)){
            return response()->json(['status'=>false,'message'=>$data],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function showDetailsProductArrayAttribute($id,Request $request){
        try{
        $data=$this->productRepo->showDetailsProductArrayAttribute($this->product_array_attribute,$id,$request);
                if(is_string($data)){
            return response()->json(['status'=>false,'message'=>$data],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function addToCart($word,Request $request){
        try{
        $data=$this->productRepo->addToCart($this->product,$word,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    
      public function showAttributeIdForArray(Request $request,$id){
           // try{
          $data=$request->all();
          $ProductArrayAttributes=ProductArrayAttribute::where(['product_id'=>$id])->get();
         $arr=[];
          $attrs=json_decode($data['attributes'],true);

                      //convert this price that in dinar into currency user
            $location = geoip(request()->ip());
            $currencyCountry=$location->currency;
            $ProductArrayAttributes->currency_country=$currencyCountry;
            $currencySystem='KWD';
            if($location->currency!==$currencySystem){
              foreach($ProductArrayAttributes as $ProductArrayAttribute){
            //   dd($ProductArrayAttribute->attributes);
    
                 if($ProductArrayAttribute->attributes==$attrs)//من اول م تلاقيه هاتلي اياه يعني 
                 {
                       $convertingCurrenciesOriginalPrice=  Currency::convert()
                    ->from($currencySystem)
                    ->to($currencyCountry)
                    ->amount($ProductArrayAttribute->original_price)
                    ->get();
                    $ProductArrayAttribute->original_price=round($convertingCurrenciesOriginalPrice,2);
                    
                   $convertingCurrenciesPriceEnds=  Currency::convert()
                    ->from($currencySystem)
                    ->to($currencyCountry)
                    ->amount($ProductArrayAttribute->price_discount_ends)
                    ->get();
                    $ProductArrayAttribute->price_discount_ends=round($convertingCurrenciesPriceEnds,2);
                    
                          return response()->json(['status'=>true,'message'=>'تم ايجاد المواصفات','data'=>$ProductArrayAttribute],200);
    
                 }
    
              }
                          return response()->json(['status'=>false,'message'=>'غير موجود'],404);
    
            //  return $attrs;
            // 
             $result= in_array($attrs,$arr);
    
            if(!$result){
                return response()->json(['status'=>false,'message'=>'غير موجود'],404);
            }else{
                return response()->json(['status'=>true,'message'=>'تم ايجاد المواصفات ','data'=>$attrs],200);
            }
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$result],200);
    
            
            // }catch(\Exception $ex){
            //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
            // } 
        }
            }
}
