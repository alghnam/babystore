<?php

namespace Modules\Cart\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cart\Entities\Cart;
use Modules\Cart\Http\Requests\AddToCartRequest;
use Modules\Cart\Repositories\User\CartRepository;
use Modules\Auth\Entities\User;
use AmrShawky\LaravelCurrency\Facade\Currency;

class CartController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var CartRepository
    */
    protected $cartRepo;
    /**
    * @var Cart
    */
    protected $cart;


    /**
    * CartsController constructor.
    *
    * @param CartRepository $carts
    */
    public function __construct(BaseRepository $baseRepo, Cart $cart,CartRepository $cartRepo)
    {

    $this->baseRepo = $baseRepo;
    $this->cart = $cart;
    $this->cartRepo = $cartRepo;
    }
 //for user 
        public function selectAttribute(Request $request,$productId)
        {
            try{
                $cart=$this->cartRepo->selectAttribute($request,$productId);
                if(is_string($cart)){
                    return response()->json(['status'=>false,'message'=>$cart],404);
                }
               $data=[
                   'attribute_id'=>$cart->id
                   ];
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cart],200);


        
            }catch(\Exception $ex){
                return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
            } 
               
          
    }
    public function addToCart($attributeId,AddToCartRequest $request)
    {
        try{
            $cart=$this->cartRepo->addToCart($this->cart,$attributeId,$request);
                if(is_string($cart)){
                return response()->json(['status'=>false,'message'=>$cart],404);
            }
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cart],200);


        
            }catch(\Exception $ex){
                return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
            } 
        
    }
    public function    getCountProductsCart(){
        // try{
            $cart=$this->cartRepo->getCartUser($this->cart);
            if(is_string($cart)){
                return response()->json(['status'=>false,'message'=>$cart],404);
            }             $data=[
                    'count_products_cart'=>count($cart->productArrayAttributes)
                    ];                
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);


        
            // }catch(\Exception $ex){
            //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
            // } 
    }
        public function getCartUser(){
            // try{
            $cart=$this->cartRepo->getCartUser($this->cart); 
                if(is_string($cart)){
                    return response()->json(['status'=>false,'message'=>$cart],404);
                }
                $user=auth()->guard('api')->user();
                if($user==null){
            //convert this price that in dinar into currency user
                    $location = geoip(request()->ip());
                    $cart->currency_country=$location->currency;
                    if($location->currency!==config('constants.currency_system')){
                        if($cart->productArrayAttributes){
                          foreach($cart->productArrayAttributes as $proAttr){
                                if($proAttr->price_discount_ends){
                                      $convertingPriceEnds =  $this->baseRepo->priceCalculation($proAttr->price_discount_ends);
                                    $proAttr->price_discount_ends=$convertingPriceEnds;
                                }
                          }
                        }
                    }
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cart],200);

                }else{
                    if(count($user->addresses)!==0){
                        $is_address=1;
                    }else{
                        $is_address=0;
                    }
                    //convert this price that in dinar into currency user
                    $location = geoip(request()->ip());
                    $cart->currency_country=$location->currency;
                    if($location->currency!==config('constants.currency_system')){
                        if($cart->productArrayAttributes){
                          foreach($cart->productArrayAttributes as $proAttr){
                                if($proAttr->price_discount_ends){
                                      $convertingPriceEnds =  $this->baseRepo->priceCalculation($proAttr->price_discount_ends);
                                    $proAttr->price_discount_ends=$convertingPriceEnds;
                                }
                          }
                        }
                    }
                    $data=[
                        'is_address'=>$is_address,
                        'count_products_cart'=>count($cart->productArrayAttributes),
                        'cart'=>$cart
                        ];
                    return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$data],200);


        
                }
            // }catch(\Exception $ex){
            //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
            // } 
        

    
    }
    
    public function removeProductFromCart($productId){
        // try{
            $removeProductFromCart=$this->cartRepo->removeProductFromCart($this->cart,$productId);
            if(is_string($removeProductFromCart)){
                return response()->json(['status'=>false,'message'=>$removeProductFromCart],400);
            }
            
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$removeProductFromCart],200);


        
            // }catch(\Exception $ex){
            //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
            // } 
        
    }
    
    public function deleteAllQuantitiesProduct($product_array_attribute_id){
        // try{
             $deleteAllQuantitiesProduct=$this->cartRepo->deleteAllQuantitiesProduct($this->cart,$product_array_attribute_id);
            if(is_string($deleteAllQuantitiesProduct)){
                return response()->json(['status'=>false,'message'=>$deleteAllQuantitiesProduct],400);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$deleteAllQuantitiesProduct],200);


        
            // }catch(\Exception $ex){
            //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
            // } 
    }
}
