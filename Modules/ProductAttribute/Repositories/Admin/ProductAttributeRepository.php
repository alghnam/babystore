<?php
namespace Modules\ProductAttribute\Repositories\Admin;

use App\Repositories\EloquentRepository;

use Modules\ProductAttribute\Repositories\Admin\ProductAttributeRepositoryInterface;
use Modules\ProductAttribute\Entities\ProductAttribute;
use Modules\Product\Entities\Product;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use Illuminate\Support\Arr;
use App\GeneralClasses\MediaClass;
use DB;
class ProductAttributeRepository extends EloquentRepository implements ProductAttributeRepositoryInterface
{

    public function getAllProductAttributesPaginate($model,$request){
    $modelData=$model->with('product.category.mainCategory')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
   
    }
    public function getProductAttributesForProduct($model,$request,$productId){
    $modelData=$model->where(['product_id'=>$productId])->with('product')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
    }
    
       public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->with('product.category.mainCategory')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
        return $modelData;
    }
        public function store($request,$model){
            $data= $request->validated();
          $attrsProduct=  ProductAttribute::where(['product_id'=>$data['product_id']])->get();
          if(count($attrsProduct)!=0){
              foreach($attrsProduct as $attrProduct){
                if(json_decode($attrProduct->attributes)==$data['attributes']){
                return 'لا يمكنك الاضافة مرة اخرى';
                    
                }
              }
          }
            $ProductAttribute=new ProductAttribute();
            $ProductAttribute->product_id=$data['product_id'];
            $ProductAttribute->option=$data['option'];
            $ProductAttribute->attributes=json_encode($data['attributes']);
            $ProductAttribute->save();
            return $ProductAttribute;

            }
            public function update($request,$id,$model){
                $data= $request->validated();
                // $arrAttributes = explode(',', $data['attributes']);
                $productAttribute=$this->find($id,$model);
                $productAttribute->option=$data['option'];
                $productAttribute->attributes=$data['attributes'];
                $productAttribute->save();
                return $productAttribute;
            }
            public function saveManyAttributes($request,$model){
                $data= $request->validated();//req : product_id , attrs
                foreach($data['attributes'] as $v){
                    if($v['value']==null){

                        return 'يجب عليك الاختيار من جميع الخيارات او عليك اعادة تحميل الصفحة';
                    }
                }
                $ProductArrayAttribute= new  ProductArrayAttribute();
                $ProductArrayAttribute->product_id=$data['product_id'];
                $ProductArrayAttribute->attributes=$data['attributes'];
                $ProductArrayAttribute->save();
                return $ProductArrayAttribute;

            }
            public function updateManyAttributes($productId,$request,$model){
                $data= $request->validated();//req : product_id , attrs
                $product_array_attributes= DB::table('product_array_attributes')->where(['product_id'=>$productId])->update(['attributes'=>$data['attributes']]);
                return $product_array_attributes;
            }                        
            public function deleteManyAttributes($id,$model){
                $product_array_attributes= DB::table('product_array_attributes')->where(['id'=>$id])->delete();
                return $product_array_attributes;
            }
            public function saveDetailsArrayProductAttributes($request,$model){
                 $data= $request->validated();//req : product_id , attrs
                             if($data['original_price']==0){
            return 'يجب ان يتجاوز السعر الاصلي  : الصفر';
        }
                    if($data['price_discount_ends']==0){
            return 'يجب ان يتجاوز السعر النهائي بعد الخصم :  الصفر';
        }
        if($data['original_price']<$data['price_discount_ends']){
            return 'يجب ان يكون السعر النهائي بعد الخصم اقل من السعر الاصلي للمنتج';
        }
                 $model->insert($data);
                            return $product_details_array_attributes;
            }            
            public function updateDetailsArrayProductAttributes($id,$request,$model){
                 $data= $request->validated();//req : product_id , attrs
                             if($data['original_price']==0){
            return 'يجب ان يتجاوز السعر الاصلي  : الصفر';
        }
                    if($data['price_discount_ends']==0){
            return 'يجب ان يتجاوز السعر النهائي بعد الخصم :  الصفر';
        }
        if($data['original_price']<$data['price_discount_ends']){
            return 'يجب ان يكون السعر النهائي بعد الخصم اقل من السعر الاصلي للمنتج';
        }
                $enteredData=  Arr::except($data ,['image']);
               $productAttribute= ProductArrayAttribute::where('id',$id)->first();
               $productAttribute->update($enteredData);
                               $product =Product::find($data['product_id']);
                 
                 $totalQuantityAttrs=0;
                 $totalPriceAttrs=0;
                 $attrsProduct=$model::where(['product_id'=>$data['product_id']])->get();
                 foreach($attrsProduct as $attrProduct){
                 $totalQuantityAttrs=$totalQuantityAttrs + $attrProduct->quantity;
                 $totalPriceAttrs=$totalPriceAttrs + $attrProduct->price_discount_ends;
                 }
                 if($totalQuantityAttrs>$product->quantity){
                     return 'لا يمكنك اضافة هذه الكمية , يجب عليك تقليلها لتتناسب مع كمية المنتج الاصلي الموجود بالمخزن';
                 }

                //  if($totalPriceAttrs>(int)$product->price_discount_ends){
                //      return 'لا يمكنك اضافة هذا السعر , يجب عليك تقليله ليتناسب مع السعر الاصلي للمنتج الاصلي';
                //  }


                if(!empty($data['image'])){
                   if($request->hasFile('image')){
                       $file_path_original= MediaClass::store($request->file('image'),'product-attributes-images');//store productAttribute image
                        $file_path_original= str_replace("public/","",$file_path_original);

                       $data['image']=$file_path_original;
                   }else{
                       $data['image']=$productAttribute->image;
                   }
                //   dd($data['image']);
                 if($productAttribute->image){
                     
                     $productAttribute->image()->update(['url'=>$data['image'],'imageable_id'=>$productAttribute->id,'imageable_type'=>'Modules\ProductAttribute\Entities\ProductArrayAttribute']);
                 }else{
                     $productAttribute->image()->create(['url'=>$data['image'],'imageable_id'=>$productAttribute->id,'imageable_type'=>'Modules\ProductAttribute\Entities\ProductArrayAttribute']);
                 }
             }
            //  $image=$data['image'];
            // $img= str_replace("public/","",$image);
            // dd($productAttribute->image);
            // if($productAttribute->image){
            //     $productAttribute->image->url=$img;
                
            // }
            return $productAttribute;
            }
            
    //             public function destroyAttr($productId,$name,$attrs,$model){

    //             //   $item= ProductAttribute::where(['product_id'=>$productId,'attributes'=>$name,'option'=>json_decode($attrs)])->first();
    //             if(empty($item)){
    //                 return 'غير موجود بالنظام';
    //             }
    //     //this item not found in table items
    //     if($item->deleted_at!==null){
    //         return 'هذا العنصر بالفعل تم حذفه من قبل بشكل مؤقت';
    //     }
    //     $item->delete($item);
        
    //     return $item;
    // }
    public function destroy($id,$item){
        if($id=="undefined"){
            return 'من فضلك اعد تحميل الصفحة بعد ذلك قم بحذف هذا العنصر';
        }
        $item=$this->find($id,$item);
        if(is_string($item)){
            return $item;
        }
        //this item not found in table items
        if($item->deleted_at!==null){
            return 'هذا العنصر بالفعل تم حذفه من قبل بشكل مؤقت';
        }
        $item->delete($item);
        
        return $item;
    }

    
}
