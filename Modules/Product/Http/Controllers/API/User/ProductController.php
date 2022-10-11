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
       $getLocation=$this->productRepo->getLocation($this->product);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'getLocation  has been getten successfully',
            'data'=> $getLocation
        ]);
        return \response()->json([
            'code'=>200,
            'status'=>true,
            'message'=>'location user has been getten successfully',
            'data'=>$getLocation
        ]);

    }


     public function getMoreSaleProducts(Request $request){
        $getMoreSaleProducts=$this->productRepo->getMoreSaleProducts($this->product,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'getMoreSaleProducts  has been getten successfully',
            'data'=> $getMoreSaleProducts
        ]);
    }
    public function getModernProducts(Request $request){
        $getModernProducts=$this->productRepo->getModernProducts($this->product,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'products  has been getten successfully',
            'data'=> $getModernProducts
        ]);
    }
    public function getOffersProducts(Request $request){
        $getOffersProducts=$this->productRepo->getOffersProducts($this->product,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'products  has been getten successfully',
            'data'=> $getOffersProducts
        ]);
    }
    
     public function getMoreSaleProductsHome(Request $request){
        $getMoreSaleProducts=$this->productRepo->getMoreSaleProducts($this->product,$request);
        // return response()->json([
        //     'status'=>true,
        //     'code' => 200,
        //     'message' => 'getMoreSaleProducts  has been getten successfully',
        //     'data'=> $getMoreSaleProducts
        // ]);
        return $getMoreSaleProducts;
    }
    public function getModernProductsHome(Request $request){
        $getModernProducts=$this->productRepo->getModernProducts($this->product,$request);
        // return response()->json([
        //     'status'=>true,
        //     'code' => 200,
        //     'message' => 'products  has been getten successfully',
        //     'data'=> $getModernProducts
        // ]);
        return $getModernProducts;
    }
    public function getOffersProductsHome(Request $request){
        $getOffersProducts=$this->productRepo->getOffersProducts($this->product,$request);
        // return response()->json([
        //     'status'=>true,
        //     'code' => 200,
        //     'message' => 'products  has been getten successfully',
        //     'data'=> $getOffersProducts
        // ]);
        return $getOffersProducts;
    }
    
    
    public function getAllDataProductsInHome(Request $request){
        $getMoreSaleProducts=$this->getMoreSaleProductsHome($request);
        $getModernProducts=$this->getModernProductsHome($request);
        $getOffersProducts=$this->getOffersProductsHome($request);
        $data=[
            'getMoreSaleProducts'=>$getMoreSaleProducts,
            'getModernProducts'=>$getModernProducts,
            'getOffersProducts'=>$getOffersProducts
            ];
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'get all data products in home has been getten successfully',
            'data'=> $data
        ]);
    }
    public function getProductsForCategory($categoryId,Request $request){
        $getProductsForCategory=$this->productRepo->getProductsForCategory($this->product,$categoryId,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'productsForCategory  has been getten successfully',
            'data'=> $getProductsForCategory
        ]);
    }
        public function getProductsForSubCategoryTable($subCategoryId,Request $request){
        $getProductsForCategory=$this->productRepo->getProductsForSubCategoryTable($this->product,$subCategoryId,$request);
        if(is_numeric($getProductsForCategory)){
                         if($getProductsForCategory==400){
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
                'message' => 'productsForCategory  has been getten successfully',
                'data'=> $getProductsForCategory
            ]);
        }
    }


    public function search($word){
        $search=$this->productRepo->search($this->product,$word);
    // return response()->json($search, 200,['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $search
        ]);
    }

    public function searchMoreSale($word,Request $request){
        $searchMoreSale=$this->productRepo->searchMoreSale($this->product,$word,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchMoreSale
        ]);
    }
    public function searchModern($word,Request $request){
        $searchModern=$this->productRepo->searchModern($this->product,$word,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchModern
        ]);
    }
    public function searchOffers($word,Request $request){
        $searchOffers=$this->productRepo->searchOffers($this->product,$word,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchOffers
        ]);
    }
    public function searchProductsSpesificPriceSpesificWord($word,$price1,$price2,Request $request){
        $searchProductsSpesificPriceSpesificWord=$this->productRepo->searchProductsSpesificPriceSpesificWord($this->product,$word,$price1,$price2,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchProductsSpesificPriceSpesificWord
        ]);
    }
    public function searchPriceMoreSale($word,$price1,$price2,Request $request){
        $searchPriceMoreSale=$this->productRepo->searchPriceMoreSale($this->product,$word,$price1,$price2,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchPriceMoreSale
        ]);
    }


    public function searchPriceOffers($word,$price1,$price2,Request $request){
        $searchPriceOffers=$this->productRepo->searchPriceOffers($this->product,$word,$price1,$price2,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchPriceOffers
        ]);
    }
    public function searchProductsSpesificCategorySpesificWord($categoryId,$word,Request $request){
                $searchProductsSpesificCategorySpesificWord=$this->productRepo->searchProductsSpesificCategorySpesificWord($this->product,$categoryId,$word,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchProductsSpesificCategorySpesificWord
        ]);
    }
    public function searchProductsSpesificCategorySpesificWordOrderMoreSale($word,Request $request){
        $searchProductsSpesificCategorySpesificWordOrderMoreSale=$this->productRepo->searchProductsSpesificCategorySpesificWordOrderMoreSale($this->product,$word,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchProductsSpesificCategorySpesificWordOrderMoreSale
        ]);
    }
    public function searchPriceModern($word,$price1,$price2,Request $request){
        $searchPriceModern=$this->productRepo->searchPriceModern($this->product,$word,$price1,$price2,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchPriceModern
        ]);
    }
    public function searchProductsSpesificCategorySpesificWordOrderOffers($word,Request $request){
        $searchProductsSpesificCategorySpesificWordOrderOffers=$this->productRepo->searchProductsSpesificCategorySpesificWordOrderOffers($this->product,$word,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchProductsSpesificCategorySpesificWordOrderOffers
        ]);
    }

    public function searchProductsSpesificCategoryAndPriceSpesificWord($word,Request $request){
        $searchProductsSpesificCategoryAndPriceSpesificWord=$this->productRepo->searchProductsSpesificCategoryAndPriceSpesificWord($this->product,$word,$request);
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'data  has been getten successfully',
            'data'=> $searchProductsSpesificCategoryAndPriceSpesificWord
        ]);
    }
    public function showProductWithRelations($id){
        // dd($id);

        $showProductWithRelations=$this->productRepo->showProductWithRelations($this->product,$id);
        if(empty($showProductWithRelations)){
                        return response()->json(['status'=>false,'message'=>'غير موجود'],404);

        }
        // if($showProductWithRelations)
        // dd($showProductWithRelations);
         $userIp = request()->ip();
                        $location = geoip($userIp);
    
            //convert this price that in dinar into currency user
            $currencySystem='KWD';
            $currencyCountry=$location->currency;
            if($showProductWithRelations->original_price){
                
              $convertingCurrencies=  Currency::convert()
            ->from($currencySystem)
            ->to($currencyCountry)
            ->amount($showProductWithRelations->original_price)
            ->get();
            $data=[
                'original price of the product'=>$convertingCurrencies,
                'product_details'=>$showProductWithRelations
                ];
            }else{
                                
            
            $data=[
                'original_price'=>null,
                'product_details'=>$showProductWithRelations
                ];
            }

           return response()->json([
            'status'=>true,
            'message' => 'data  has been getten successfully',
            'data'=> $data
        ]); 
        
        
    }

public function showAttributesProduct($id){
        $showAttributesProduct=$this->productRepo->showAttributesProduct($this->product,$id);
                        if(is_string($showAttributesProduct)){
            return response()->json(['status'=>false,'message'=>$showAttributesProduct],404);
        }
        return response()->json([
            'status'=>true,
            'message' => 'data  has been getten successfully',
            'data'=> $showAttributesProduct
        ]);
    }
    public function showDetailsProductArrayAttribute($id,Request $request){
        $showDetailsProductArrayAttribute=$this->productRepo->showDetailsProductArrayAttribute($this->product_array_attribute,$id,$request);
                if(is_string($showDetailsProductArrayAttribute)){
            return response()->json(['status'=>false,'message'=>$showDetailsProductArrayAttribute],404);
        }
        return response()->json([
            'status'=>true,
            'message' => 'data  has been getten successfully',
            'data'=> $showDetailsProductArrayAttribute
        ]);
    }
    public function addToCart($word,Request $request){
        $addToCart=$this->productRepo->addToCart($this->product,$word,$request);
        return response()->json([
            'status'=>true,
            'message' => 'product   has been added into your cart successfully',
            'data'=> $addToCart
        ]);
    }
    
      public function showAttributeIdForArray(Request $request,$id){

          $data=$request->all();
          $ProductArrayAttributes=ProductArrayAttribute::where(['product_id'=>$id])->get();
         $arr=[];
          $attrs=json_decode($data['attributes'],true);
          //حيضل يلف ويقارن ولو لقاه عطول حيحكيو تمام لقيته ولو ما لقاه حيضل يلف ولو خللص لف خلص حيطلع برة ويحكيلو هلص مش موجود 
          foreach($ProductArrayAttributes as $ProductArrayAttribute){

             if($ProductArrayAttribute->attributes==$attrs)//من اول م تلاقيه هاتلي اياه يعني 
             {
                      return response()->json(['status'=>true,'message'=>'تم ايجاد المواصفات','data'=>$ProductArrayAttribute],200);

             }

          }
                      return response()->json(['status'=>false,'message'=>'غير موجود'],404);

        //  return $attrs;
        // 
        dd($attrs);
         $result= in_array($attrs,$arr);
        

                      if(!$result){
            return response()->json(['status'=>false,'message'=>'غير موجود'],404);
        }else{
            dd(7);
            return response()->json(['status'=>true,'message'=>'تم ايجاد المواصفات ','data'=>$attrs],200);
        }
              return response()->json([
            'status'=>true,
            'message' => 'id for attributes  has been getten  successfully',
            'data'=> $result
        ]);
    }
}
