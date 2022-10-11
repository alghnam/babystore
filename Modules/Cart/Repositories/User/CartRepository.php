<?php
namespace Modules\Cart\Repositories\User;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Auth\Entities\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Repositories\User\CartRepositoryInterface;
use DB;
    use Illuminate\Support\Facades\Auth;
    use Modules\Product\Entities\Product;
    use Modules\SubProduct\Entities\SubProduct;
    use Modules\ProductAttribute\Entities\ProductArrayAttribute;

class CartRepository extends EloquentRepository implements CartRepositoryInterface
{
   
    
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
                    if(empty($cart)){
                        // return __('cart user is empty');
                        return 'سلتك فارغة';

                    }else{
                        // return $cart->load('products','products.productImages');
                        return $cart->load('productArrayAttributes.product','productArrayAttributes','productArrayAttributes.image');
                    }
                }else{

                  $cartUser=  $model->where('user_id',$user->id)->first();
                //   dd($cartUser);
                  if(empty($cartUser)){
                      
                        // return __('cart user is empty');
                        return 'سلتك فارغة';

                  }else{
                      
                       return $cartUser->load('productArrayAttributes.product','productArrayAttributes','productArrayAttributes.image');

                  }
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
               //  dd($productWithArrayAttrs->productArrayAttributes);
                foreach($productWithArrayAttrs->productArrayAttributes as $productArrayAttribute){
                // dd($productArrayAttribute);
                 //   dd($productArrayAttribute->attributes);
                    foreach($productArrayAttribute->attributes as $attr){
                        // dd($attr);
                        
                        //  array_push($attrsProduct,$productArrayAttribute->attributes);
                         array_push($attrsProduct,$attr);
        //                  dd($attrsProduct);
        //                 foreach($attrsProduct as $attrProduct){
        //                     // dd($attrProduct);
        //                     //  $a=json_decode($attrProduct,true);
        //                      if($arrRequest===$attrProduct){
        //                         //  dd($productArrayAttribute);
        //                          return $productArrayAttribute;
        //                     }else{
        //                                 //  return __('not found');
        //  return 'غير موجود';
        //                     }
               

        //                 }
                    }
                    if($arrRequest==$attrsProduct){
                                                             return $productArrayAttribute;

                    }else{
                                                                //  return __('not found');
         return 'غير موجود';
        //                     
                    }
                    // dd($arrRequest);
                    //                          dd($attrsProduct);

                }
                     
     }else{
        //  return __('not found');
         return 'غير موجود';
     }

    }
        public function addToCart($model,$product_array_attribute_id,$request){

            try{
                $data=$request->validated();
                // dd($data);
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
                        $productCartCount= DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id])->count();
                        if($productCartCount==0){
                                                        $product_array_attribute=  ProductArrayAttribute::where('id',$product_array_attribute_id)->first();
if(empty($product_array_attribute)){
                                return 'هذا العنصر غير متواجد بالنظام';
                            }
                            if($product_array_attribute->quantity>=(int)$data['quantity']){
                                                            //descrease quantity this attribute from original quantity  for this attribute and original quantity  for this product 
                                $product_array_attribute->quantity=($product_array_attribute->quantity)-(int)$data['quantity'];
                                $product_array_attribute->save();
                                
                            DB::table('product_cart')->insert(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id,'quantity'=>(int)$data['quantity']]);
                                $product=Product::where('id',$product_array_attribute->product_id)->first();
                                $product->quantity=($product->quantity)-(int)$data['quantity'];
                                $product->save();
                          return $product_array_attribute->load('product');
                            }else{
                                // return __('there is not found enough quantity for this attribute to add this quantity into your cart');
                                return 'لا يوجد كمية كافية من هذا المنتج بهذه المواصفات لاضافته الى سلتك ';
                            }

                        }else{
                                                        $product_array_attribute=  ProductArrayAttribute::where('id',$product_array_attribute_id)->first();
if(empty($product_array_attribute)){
                                return 'هذا العنصر غير متواجد بالنظام';
                            }
                            if($product_array_attribute->quantity>=(int)$data['quantity']){
                                                            //descrease quantity this attribute from original quantity  for this attribute and original quantity  for this product 
                                $product_array_attribute->quantity=($product_array_attribute->quantity)-(int)$data['quantity'];
                                $product_array_attribute->save();
                                
                                $product=Product::where('id',$product_array_attribute->product_id)->first();
                                $product->quantity=($product->quantity)-(int)$data['quantity'];
                                $product->save();
                            $productCart= DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id])->first();
                            $productCartQuantity= $productCart->quantity;
                            $productCartQuantity=$productCartQuantity+$data['quantity'];
                            DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id])->update(['quantity'=>$productCartQuantity]);

                                                      return $product_array_attribute->load('product');
                            }else{
                                // return __('there is not found enough quantity for this attribute to add this quantity into your cart');
                                return 'لا يوجد كمية كافية من هذا المنتج بهذه المواصفات لاضافته الى سلتك ';
                                                        }


                        }
            
                    }else{
            
                        $cart= new $model;
                        $cart->session_id=$session_id;
                        $cart->save();

                                                    $product_array_attribute=  ProductArrayAttribute::where('id',$product_array_attribute_id)->first();
if(empty($product_array_attribute)){
                                return 'هذا العنصر غير متواجد بالنظام';
                            }
                            if($product_array_attribute->quantity>=(int)$data['quantity']){
                                //descrease quantity this attribute from original quantity  for this attribute and original quantity  for this product 
                                $product_array_attribute->quantity=($product_array_attribute->quantity)-(int)$data['quantity'];
                                $product_array_attribute->save();
                                
                        DB::table('product_cart')->insert(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id,'quantity'=>(int)$data['quantity']]);
                                $product=Product::where('id',$product_array_attribute->product_id)->first();
                                $product->quantity=($product->quantity)-(int)$data['quantity'];
                                $product->save();        

                                                  return $product_array_attribute->load('product');

                            }else{
                                // return __('there is not found enough quantity for this attribute to add this quantity into your cart');
                                return 'لا يوجد كمية كافية من هذا المنتج بهذه المواصفات لاضافته الى سلتك ';
                                                        }

                    }
                }else{
                    $cart=$model->where('user_id',$user->id)->first();
                    
                    if(!empty($cart)){

                        $cart->user_id=$user->id;
                        $cart->save();
                        $productCartCount= DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id])->count();
                        if($productCartCount==0){
                                                        $product_array_attribute=  ProductArrayAttribute::where('id',$product_array_attribute_id)->first();
if(empty($product_array_attribute)){
                                return 'هذا العنصر غير متواجد بالنظام';
                            }
                            if($product_array_attribute->quantity>=(int)$data['quantity']){
                                //descrease quantity this attribute from original quantity  for this attribute and original quantity  for this product 
                                $product_array_attribute->quantity=($product_array_attribute->quantity)-(int)$data['quantity'];
                                $product_array_attribute->save();
                                
                                DB::table('product_cart')->insert(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id,'quantity'=>(int)$data['quantity']]);
                                $product=Product::where('id',$product_array_attribute->product_id)->first();
                                $product->quantity=($product->quantity)-$data['quantity'];
                                $product->save();

                                                                              return $product_array_attribute->load('product');
                            }else{
 // return __('there is not found enough quantity for this attribute to add this quantity into your cart');
                                return 'لا يوجد كمية كافية من هذا المنتج بهذه المواصفات لاضافته الى سلتك ';
                                                        }

                        }else{

                            $product_array_attribute=  ProductArrayAttribute::where('id',$product_array_attribute_id)->first();
                            if(empty($product_array_attribute)){
                                return 'هذا العنصر غير متواجد بالنظام';
                            }
                            if($product_array_attribute->quantity>(int)$data['quantity']||$product_array_attribute->quantity==(int)$data['quantity']){
                                
                                //descrease quantity this attribute from original quantity  for this attribute and original quantity  for this product 
                                $product_array_attribute->quantity=($product_array_attribute->quantity)-(int)$data['quantity'];
                                $product_array_attribute->save();
                                
                                $product=Product::where('id',$product_array_attribute->product_id)->first();
                                $product->quantity=($product->quantity)-(int)$data['quantity'];
                                $product->save();
                                $productCart= DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id])->first();
                               // dd($productCart);
                                //increase quantity this attribute in cart  
                                $productCartQuantity= $productCart->quantity;
                                $productCartQuantity=$productCartQuantity+(int)$data['quantity'];
                 //   dd($cart->id);
                                DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id])->update(['quantity'=>$productCartQuantity]);
                                return $product_array_attribute->load('product');
                            }else{
 // return __('there is not found enough quantity for this attribute to add this quantity into your cart');
                                return 'لا يوجد كمية كافية من هذا المنتج بهذه المواصفات لاضافته الى سلتك ';
                                                        }


                        }
                    }else{


                        $cart= new $model;
                        $cart->user_id=$user->id;
                        $cart->save();
                        $productCartCount= DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id])->count();
                        if($productCartCount==0){
                                                        $product_array_attribute=  ProductArrayAttribute::where('id',$product_array_attribute_id)->first();
if(empty($product_array_attribute)){
                                return 'هذا العنصر غير متواجد بالنظام';
                            }
                            dd(4);
                            if($product_array_attribute->quantity>=(int)$data['quantity']){
                                //descrease quantity this attribute from original quantity  for this attribute and original quantity  for this product 
                                $product_array_attribute->quantity=($product_array_attribute->quantity)-(int)$data['quantity'];
                                $product_array_attribute->save();
                        DB::table('product_cart')->insert(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id,'quantity'=>(int)$data['quantity']]);
                                
                                $product=Product::where('id',$product_array_attribute->product_id)->first();
                                $product->quantity=($product->quantity)-(int)$data['quantity'];
                                $product->save();

                          return $product_array_attribute->load('product');
                            }else{
 // return __('there is not found enough quantity for this attribute to add this quantity into your cart');
                                return 'لا يوجد كمية كافية من هذا المنتج بهذه المواصفات لاضافته الى سلتك ';
                                                        }

                        }else{
                                                        $product_array_attribute=  ProductArrayAttribute::where('id',$product_array_attribute_id)->first();
                                                        if(empty($product_array_attribute)){
                                return 'هذا العنصر غير متواجد بالنظام';
                            }

                            if($product_array_attribute->quantity>=(int)$data['quantity']){
                                                            //descrease quantity this attribute from original quantity  for this attribute and original quantity  for this product 
                                $product_array_attribute->quantity=($product_array_attribute->quantity)-(int)$data['quantity'];
                                $product_array_attribute->save();
                                
                                $product=Product::where('id',$product_array_attribute->product_id)->first();
                                $product->quantity=($product->quantity)-$data['quantity'];
                                $product->save();
                            $productCart= DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id])->first();
                            $productCartQuantity= $productCart->quantity;
                            $productCartQuantity=$productCartQuantity+$data['quantity'];
                            DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$cart->id])->update(['quantity'=>$productCartQuantity]);

                                                      return $product_array_attribute->load('product');
                            }else{
 // return __('there is not found enough quantity for this attribute to add this quantity into your cart');
                                return 'لا يوجد كمية كافية من هذا المنتج بهذه المواصفات لاضافته الى سلتك ';
                                                        }


                        }            
                        
                    }   
                }
            }catch(\Exception $ex){
         Storage::put('session_id',null);
 
            }
            
            
    }
    
    public function removeProductFromCart($model,$product_array_attribute_id){
        $user=auth()->guard('api')->user();
        
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
                  $productsCartUser = DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$userCart->id])->get();
                  if(count($productsCartUser)==0){
                    //   return __('not found this product in your cart to delete it');
                      return 'لا يوجد اي منتج في سلتك';
                  }
                  foreach($productsCartUser as $productCartUser){
                      if($productCartUser->quantity==1){
                          
                    //  $productCartUser->delete();
                            $cart= $model->where('id',$userCart->id)->first();
        $cart->productArrayAttributes()->detach($product_array_attribute_id);
                      }else{
                       $ProductArrayAttribute=   ProductArrayAttribute::where(['id'=>$product_array_attribute_id])->first();
                                              if(empty($ProductArrayAttribute)){
                           return 'هذا المنتج غير موجود بالنظام';
                       }
$productCartUser = $ProductArrayAttribute->carts()->find($userCart->id);
                            $productCartUser->pivot->quantity = $productCartUser->pivot->quantity  -1 ;
                            $productCartUser->pivot->save();
                      }
                  }
            }else{
                $userCart=   $model->where(['session_id'=>$session_id])->first();

                if(empty($userCart)){
                    $model->create(['session_id'=>$session_id]);
                }
                  $productsCartUser = DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$userCart->id])->get();
                   if(count($productsCartUser)==0){
                    //   return __('not found this product in your cart to delete it ');
                       return 'هذا المنتج ليس في سلتك لحذفه';
                  }
                  foreach($productsCartUser as $productCartUser){
                      if($productCartUser->quantity==1){
                          
                        $cart= $model->where('id',$userCart->id)->first();
                        $cart->productArrayAttributes()->detach($product_array_attribute_id);
                      }else{
  $ProductArrayAttribute=   ProductArrayAttribute::where(['id'=>$product_array_attribute_id])->first();
                         if(empty($ProductArrayAttribute)){
                           return 'هذا المنتج غير موجود بالنظام';
                       }
$productCartUser = $ProductArrayAttribute->carts()->find($userCart->id);

                            $productCartUser->pivot->quantity = $productCartUser->pivot->quantity  -1 ;
                            $productCartUser->pivot->save();
                      }                  
                      
                  }
            }
        }else{
            

         $userCart=   $model->where(['user_id'=>$user->id])->first();

         if(empty($userCart)){
                                 $model->create(['user_id'=>$user->id]);
         }
          $productsCartUser = DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$userCart->id])->get();
          if(count($productsCartUser)==0){
  //   return __('not found this product in your cart to delete it ');
                       return 'هذا المنتج ليس في سلتك لحذفه';
                  
                              }
        $cart= $model->where('id',$userCart->id)->first();
                  foreach($productsCartUser as $productCartUser){
                      if($productCartUser->quantity==1){
                          
                        $cart= $model->where('id',$userCart->id)->first();
                        $cart->productArrayAttributes()->detach($product_array_attribute_id);
                      }else{
                       $ProductArrayAttribute=   ProductArrayAttribute::where(['id'=>$product_array_attribute_id])->first();
                       if(empty($ProductArrayAttribute)){
                           return 'هذا المنتج غير موجود بالنظام';
                       }
$productCartUser = $ProductArrayAttribute->carts()->find($userCart->id);
                            //descrease quantity from productCartUser table
                            // if($productCartUser->pivot->quantity==0){
                                
                            // }
                            $productCartUser->pivot->quantity = $productCartUser->pivot->quantity  -1 ;
                            $productCartUser->pivot->save();
                            //increase quantity into productArrayAttribute
                            $ProductArrayAttribute->quantity=$ProductArrayAttribute->quantity+1;
                            $ProductArrayAttribute->save();
                           //increase quantity into product
                         $product=  Product::where(['id'=>$ProductArrayAttribute->product_id])->first();
                            $product->quantity=$product->quantity+1;
                            $product->save();
                      }                  
                      
                  }                  

        }
        
    }
    
    public function deleteAllQuantitiesProduct($model,$product_array_attribute_id){
        
                $user=auth()->guard('api')->user();
        
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
                  $productsCartUser = DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$userCart->id])->get();
                 // dd($productsCartUser);
                  if(count($productsCartUser)==0){
                    //   return __('not found this product in your cart to delete it');
                      return 'لا يوجد اي منتج في سلتك';
                  }
                  foreach($productsCartUser as $productCartUser){
                       $cart= $model->where('id',$userCart->id)->first();
                        // delete  this product with its quantities from cart user, will increase it in productArrayAttribute and product 
                        
                                               $ProductArrayAttribute=   ProductArrayAttribute::where(['id'=>$product_array_attribute_id])->first();
                                                                      if(empty($ProductArrayAttribute)){
                           return 'هذا المنتج غير موجود بالنظام';
                       }
                                               $ProductArrayAttribute->quantity=$ProductArrayAttribute->quantity+$productCartUser->quantity;
                                               $ProductArrayAttribute->save();
                                               
                                               $product=Product::where(['id'=>$ProductArrayAttribute->product_id])->first();
                                               $product->quantity=$product->quantity+$productCartUser->quantity;
                                               $product->save();
                                               
                                               
                        $cart->productArrayAttributes()->detach($product_array_attribute_id);
                  }
            }else{
                $userCart=   $model->where(['session_id'=>$session_id])->first();

                if(empty($userCart)){
                    $model->create(['session_id'=>$session_id]);
                }
                  $productsCartUser = DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$userCart->id])->get();
                                    // dd($productsCartUser);

                   if(count($productsCartUser)==0){
                    //   return __('not found this product in your cart to delete it ');
                       return 'هذا المنتج ليس في سلتك لحذفه';
                  }
                  foreach($productsCartUser as $productCartUser){
                           $cart= $model->where('id',$userCart->id)->first();
                        // delete  this product with its quantities from cart user, will increase it in productArrayAttribute and product 
                        
                                               $ProductArrayAttribute=   ProductArrayAttribute::where(['id'=>$product_array_attribute_id])->first();
                                                                      if(empty($ProductArrayAttribute)){
                           return 'هذا المنتج غير موجود بالنظام';
                       }
                                               $ProductArrayAttribute->quantity=$ProductArrayAttribute->quantity+$productCartUser->quantity;
                                               $ProductArrayAttribute->save();
                                               
                                               $product=Product::where(['id'=>$ProductArrayAttribute->product_id])->first();
                                               $product->quantity=$product->quantity+$productCartUser->quantity;
                                               $product->save();
                                               
                                               
                        $cart->productArrayAttributes()->detach($product_array_attribute_id);
                      
                  }
            }
        }else{
            

         $userCart=   $model->where(['user_id'=>$user->id])->first();

         if(empty($userCart)){
                                 $model->create(['user_id'=>$user->id]);
         }
          $productsCartUser = DB::table('product_cart')->where(['product_array_attribute_id'=>$product_array_attribute_id,'cart_id'=>$userCart->id])->get();
        //   dd($userCart->id);
                            // dd($productsCartUser);

          if(count($productsCartUser)==0){
  //   return __('not found this product in your cart to delete it ');
                       return 'هذا المنتج ليس في سلتك لحذفه';
                  
                              }
        $cart= $model->where('id',$userCart->id)->first();
                  foreach($productsCartUser as $productCartUser){

                        $cart= $model->where('id',$userCart->id)->first();
                        // delete  this product with its quantities from cart user, will increase it in productArrayAttribute and product 
                        
                                               $ProductArrayAttribute=   ProductArrayAttribute::where(['id'=>$product_array_attribute_id])->first();
                                                                      if(empty($ProductArrayAttribute)){
                           return 'هذا المنتج غير موجود بالنظام';
                       }
                                               $ProductArrayAttribute->quantity=$ProductArrayAttribute->quantity+$productCartUser->quantity;
                                               $ProductArrayAttribute->save();
                                               
                                               $product=Product::where(['id'=>$ProductArrayAttribute->product_id])->first();
                                               $product->quantity=$product->quantity+$productCartUser->quantity;
                                               $product->save();
                                               
                                               
                        $cart->productArrayAttributes()->detach($product_array_attribute_id);
      
                      
                  }                  

        }
        
        
    }
    
  
    
}
