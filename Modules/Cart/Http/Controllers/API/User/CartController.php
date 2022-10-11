<?php

namespace Modules\Cart\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cart\Entities\Cart;
use Modules\Cart\Http\Requests\AddToCartRequest;
use Modules\Cart\Repositories\User\CartRepository;
use Modules\Auth\Entities\User;
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
            $cart=$this->cartRepo->selectAttribute($request,$productId);
            // dd($cart);
                    if(is_string($cart)){
            return response()->json(['status'=>false,'message'=>$cart],404);
        }
               $data=[
                   'attribute_id'=>$cart->id
                   ];
                return response()->json([
                    'status'=>true,
                    'message' => 'This Attribute is exsit',
                    'data'=> $data
                ]);
               
          
    }
    public function addToCart($attributeId,AddToCartRequest $request)
    {
        
    $cart=$this->cartRepo->addToCart($this->cart,$attributeId,$request);
    // dd($cart);
            if(is_string($cart)){
            return response()->json(['status'=>false,'message'=>$cart],404);
        }
         return response()->json([
            'status'=>true,
            'message' => 'product has been added  successfully ',
            'data'=> $cart
        ]); 
        
    }
    public function    getCountProductsCart(){
        $cart=$this->cartRepo->getCartUser($this->cart);
         $data=[
                        'count_products_cart'=>count($cart->productArrayAttributes)
                        ];
                                return response()->json(['status'=>true,'message'=>'تم ايجاد العدد بنجاح','data'=>$data],200);


    }
        public function getCartUser(){
//     $user=auth()->guard('api')->user();
//     $userId=$user->id;
//   $u= User::where(['id'=>$userId])->first();
//   $roles=[];
//   $pers=[];
// //   dd($u->roles);
//   foreach($u->roles as $r){
//       array_push($roles,$r);
//   }
   
//   foreach($roles as $ro){
//       foreach($ro->permissions as $p){
//       array_push($pers,$p->name);
           
//       }
//   }
//   dd($pers);
//   foreach($pers as $i){
//       if($i=='carts_get'){
// //   dd($i);
           
//       }
//   }
   
    $cart=$this->cartRepo->getCartUser($this->cart);
                if(is_string($cart)){
            return response()->json(['status'=>false,'message'=>$cart],404);
        }
        
        $user=auth()->guard('api')->user();
                if($user==null){
                     return response()->json([
        'status'=>true,
        'message' => 'your Cart has been getten successfully',
        'data'=> $cart
    ]);
                }else{
                    if(count($user->addresses)!==0){
                        $is_address=1;
                    }else{
                        $is_address=0;
                    }
                    $data=[
                        'is_address'=>$is_address,
                        'count_products_cart'=>count($cart->productArrayAttributes),
                        'cart'=>$cart
                        ];
         return response()->json([
        'status'=>true,
        'message' => 'your Cart has been getten successfully',
        'data'=> $data
    ]);
                }
        

    
    }
    
    public function removeProductFromCart($productId){
        $removeProductFromCart=$this->cartRepo->removeProductFromCart($this->cart,$productId);
        if(is_string($removeProductFromCart)){
            return response()->json(['status'=>false,'message'=>$removeProductFromCart],400);
        }
            
            return response()->json([
                'status'=>true,
                'message' => 'product in your cart removed  been  successfully',
            ]);
        
    }
    
    public function deleteAllQuantitiesProduct($product_array_attribute_id){
         $deleteAllQuantitiesProduct=$this->cartRepo->deleteAllQuantitiesProduct($this->cart,$product_array_attribute_id);
        if(is_string($deleteAllQuantitiesProduct)){
            return response()->json(['status'=>false,'message'=>$deleteAllQuantitiesProduct],400);
        }
            
            return response()->json([
                'status'=>true,
                'message' => 'product in your cart removed  been  successfully',
            ]);
    }
}
