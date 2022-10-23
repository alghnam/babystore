<?php
namespace Modules\Cart\Repositories\User;

use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Modules\Auth\Entities\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Repositories\User\CartRepositoryInterface;
use DB;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Entities\Product;
use Modules\SubProduct\Entities\SubProduct;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use App\Repositories\BaseRepository;

class CartRepository extends EloquentRepository implements CartRepositoryInterface
{
        public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
   
    //methods recalling
        public function examinCart($cart){
             if(empty($cart)){
                return 'سلتك فارغة';

            }else{
                return $cart->load('productArrayAttributes.product','productArrayAttributes','productArrayAttributes.image');
            }
        }
        public function ExaminQuantityInStore($poduct_array_attibute_id,$cartId,$requestQuantity){

            $product_array_attribute=  ProductArrayAttribute::where('id',$poduct_array_attibute_id)->first();
            if(empty($product_array_attribute)){
                return 'هذا العنصر غير متواجد بالنظام';
            }
            if($product_array_attribute->quantity<(int)$requestQuantity){
                return 'لا يوجد كمية كافية من هذا المنتج بهذه المواصفات لاضافته الى سلتك ';

                   
               
            }else{
                $productCartCount= DB::table('product_cart')->where(['product_array_attribute_id'=>$poduct_array_attibute_id,'cart_id'=>$cartId])->count();
                if($productCartCount==0){
                        DB::table('product_cart')->insert(['product_array_attribute_id'=>$poduct_array_attibute_id,'cart_id'=>$cartId,'quantity'=>$requestQuantity]);
                        return $product_array_attribute->load('product');

                    }else{
                        $productCart= DB::table('product_cart')->where(['product_array_attribute_id'=>$poduct_array_attibute_id,'cart_id'=>$cartId])->first();
                        $productCartQuantity= $productCart->quantity;
                        $productCartQuantity=$productCartQuantity+$requestQuantity;
                        DB::table('product_cart')->where(['product_array_attribute_id'=>$poduct_array_attibute_id,'cart_id'=>$cartId])->update(['quantity'=>$productCartQuantity]);
                
                return $product_array_attribute->load('product');
                }
            }
        }


        public function deleteQuantityProductFromCart($model,$product_array_attribute_id,$userCartId,$allQuantities){

            $productsCartUser = DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$userCartId])->get();
              if(count($productsCartUser)==0){
                  return 'لا يوجد هذا المنتج في سلتك';
              }
                    $cart= $model->where('id',$userCartId)->first();
              foreach($productsCartUser as $productCartUser){
                  if($productCartUser->quantity==1){
                      if(!empty($cart)){
                          
                    $cart->productArrayAttributes()->detach($product_array_attribute_id);
                      }
                  }else{
                   $ProductArrayAttribute=   ProductArrayAttribute::where(['id'=>$product_array_attribute_id])->first();
                    if(empty($ProductArrayAttribute)){
                       return 'هذا المنتج غير موجود بالنظام';
                   }
                    if($allQuantities){
                         if(!empty($cart)){
                          
                        $cart->productArrayAttributes()->detach($product_array_attribute_id);
                      }
                    }else{
                        $productCartUser = $ProductArrayAttribute->carts()->find($userCartId);
                        $productCartUser->pivot->quantity = $productCartUser->pivot->quantity  -1 ;
                        $productCartUser->pivot->save();
                        
                    }
                  }
              }

        }


//////////////////////////////////////////////////////////////////////////////////

    public function getCartUser($model){
        $user=auth()->guard('api')->user();
        if($user==null){
            //generate session id
            $session_id=Storage::get('session_id');
            if(empty($session_id)){
                $session_id= Str::random(30);
                Storage::put('session_id',$session_id);
            }
            $cart=$model->where('session_id',$session_id)->first();
            
           return $this->examinCart($cart);
           
        }else{

            $cartUser=  $model->where('user_id',$user->id)->first();
           return $this->examinCart($cartUser);

        }
    }
    
        public function selectAttribute($request,$product_id){
            $data=$request->all();
            $product= Product::where('id',$product_id)->first();
             if(!empty($product)){
                $arrRequest = [];
                foreach($data as $d=>$key){
                  $reqFilter=str_replace('_', ' ', $d);
                    array_push($arrRequest, [
                            'name' => $reqFilter ,
                            'value' => $key,
                    ]);
                }
                $productWithArrayAttrs=$product->load('productArrayAttributes');
                $attrsProduct=[];
                if($productWithArrayAttrs){
                    foreach($productWithArrayAttrs->productArrayAttributes as $productArrayAttribute){
                        foreach($productArrayAttribute->attributes as $attr){
                            array_push($attrsProduct,$attr);
          
                        }
                        if($arrRequest==$attrsProduct){
                            return $productArrayAttribute;
    
                        }else{
                             return 'غير موجود';
                        }
                      
                    }
                }
                     
     }else{
         return 'غير موجود';
     }

    }

        public function addToCart($model,$product_array_attribute_id,$request){

            try{
                $data=$request->validated();
                $user=auth()->guard('api')->user();
                if($user==null){
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                    }
                    $cart=$model->where('session_id',$session_id)->first();
                    if(!empty($cart)){
                        $cart->session_id=$session_id;
                        $cart->save();
                        return $this->ExaminQuantityInStore($product_array_attribute_id,$cart->id,$data['quantity']);

            
                    }else{
            
                        $cart= new $model;
                        $cart->session_id=$session_id;
                        $cart->save();

                       return $this->ExaminQuantityInStore($product_array_attribute_id,$cart->id,$data['quantity']);

                    }
                }else{
                    $cart=$model->where('user_id',$user->id)->first();
                    // dd($cart->productArrayAttributes);
                    if(!empty($cart)){

                        

                       return  $this->ExaminQuantityInStore($product_array_attribute_id,$cart->id,$data['quantity']);

                    }else{


                        $cart= new $model;
                        $cart->user_id=$user->id;
                        $cart->save();

                       return $this->ExaminQuantityInStore($product_array_attribute_id,$cart->id,$data['quantity']);
          
                        
                    }   
                }
            }catch(\Exception $ex){
                Storage::put('session_id',null);
 
            }
            
            
    }
    
    public function removeProductFromCart($model,$product_array_attribute_id){
        $user=auth()->guard('api')->user();
        $allQuantities=false;
        if($user==null){
            //generate session id
            $session_id=Storage::get('session_id');
            if(empty($session_id)){
                $session_id= Str::random(30);
                Storage::put('session_id',$session_id);
                 $userCart=   $model->where(['session_id'=>$session_id])->first();

                if(empty($userCart)){
                    $model->create(['session_id'=>$session_id]);
               
                }
                $this->deleteQuantityProductFromCart($model,$product_array_attribute_id,$userCart->id,$allQuantities);
                

            }else{
                $userCart=   $model->where(['session_id'=>$session_id])->first();

                if(empty($userCart)){
                    $model->create(['session_id'=>$session_id]);
                }
                $this->deleteQuantityProductFromCart($model,$product_array_attribute_id,$userCart->id,$allQuantities);

            }
        }else{
             $userCart=   $model->where(['user_id'=>$user->id])->first();
    
             if(empty($userCart)){
                $model->create(['user_id'=>$user->id]);
             }
             
              $this->deleteQuantityProductFromCart($model,$product_array_attribute_id,$userCart->id,$allQuantities);
                     


        }
         return true;
        
    }
    
    public function deleteAllQuantitiesProduct($model,$product_array_attribute_id){
        
        $user=auth()->guard('api')->user();
        $allQuantities=true;
        if($user==null){
            //generate session id
            $session_id=Storage::get('session_id');
            if(empty($session_id)){
                $session_id= Str::random(30);
                Storage::put('session_id',$session_id);
                 $userCart=   $model->where(['session_id'=>$session_id])->first();

                if(empty($userCart)){
                    $model->create(['session_id'=>$session_id]);
               
                }
                $this->deleteQuantityProductFromCart($model,$product_array_attribute_id,$userCart->id,$allQuantities);

            }else{
                $userCart=   $model->where(['session_id'=>$session_id])->first();

                if(empty($userCart)){
                    $model->create(['session_id'=>$session_id]);
                }
                $this->deleteQuantityProductFromCart($model,$product_array_attribute_id,$userCart->id,$allQuantities);

            }
        }else{

         $userCart=   $model->where(['user_id'=>$user->id])->first();

         if(empty($userCart)){
            $model->create(['user_id'=>$user->id]);
         }
         $this->deleteQuantityProductFromCart($model,$product_array_attribute_id,$userCart->id,$allQuantities);

        }
        
        
    }
    
  
    
}
