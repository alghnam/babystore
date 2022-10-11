<?php
namespace Modules\ProductAttribute\Repositories;

use App\Repositories\EloquentRepository;

use Modules\ProductAttribute\Repositories\ProductAttributeRepositoryInterface;
use Modules\ProductAttribute\Entities\ProductAttribute;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use Illuminate\Support\Arr;
use App\GeneralClasses\MediaClass;
use DB;
class ProductAttributeRepository extends EloquentRepository implements ProductAttributeRepositoryInterface
{

    public function getAllProductAttributesPaginate($model,$request){
    $modelData=$model->where('locale',config('app.locale'))->with('product.category.mainCategory')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
   
    }
    public function getProductAttributesForProduct($model,$request,$productId){
    $modelData=$model->where(['product_id'=>$productId])->with('product')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
    }
    
       public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model)->with('product.category.mainCategory')->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
        return $modelData;
    }
                public function store($request,$model){
            $data= $request->validated();
            // $arrAttributes = implode(',', [$data['attributes']]);
            // $arrAttributess = explode(',', $arrAttributes);
ProductAttribute::create(['product_id'=>$data['product_id'],'option'=>$data['option'],'attributes'=>json_encode($data['attributes'])]);
        // $productAttribute=new ProductAttribute();
        // if(!empty($productAttribute)){
            
        //                          $productAttribute->product_id=$data['product_id'];
        //               $productAttribute->option=$data['option'];
        //           $productAttribute->attributes=$data['attributes'];
        //       $productAttribute->save();
               

            
        // }
            }
            public function update($request,$id,$model){
            $data= $request->validated();
            // dd($data);
            // $arrAttributes = explode(',', $data['attributes']);
        $productAttribute=$this->find($id,$model);
                      $productAttribute->option=$data['option'];
              $productAttribute->attributes=$data['attributes'];
              $productAttribute->save();
        // if($productAttributes->count()!==0){
        //   foreach($productAttributes as $productAttribute){
        //       $productAttribute->option=$data['option'];
        //       $productAttribute->attributes=$arrAttributes;
        //       $productAttribute->save();
        //   } 
            
        // }
        return $productAttribute;
            }
            public function saveManyAttributes($request,$model){
                            $data= $request->validated();//req : product_id , attrs
                            // dd(json_encode($data['attributes']));
                            foreach($data['attributes'] as $v){
                                // dd($v->value);
                            // dd($data['attributes']);
                            // foreach($data['attributes'] as $v){
                           //     dd($v['value']);
                                if($v['value']==null){
                                // if($v->value==null){
                                    return 'يجب عليك الاختيار من جميع الخيارات';//cannt add your request , because you must fill all inputs
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
               //  dd($data);
                           $product_details_array_attributes= DB::table('product_array_attributes')->insert($data);
                            return $product_details_array_attributes;
            }            
            public function updateDetailsArrayProductAttributes($id,$request,$model){
                 $data= $request->validated();//req : product_id , attrs
                $enteredData=  Arr::except($data ,['image']);
               $productAttribute= ProductArrayAttribute::where('id',$id)->first();
            //   $enteredData['product_array_attribute_id
               $productAttribute->update($enteredData);
               //هنا بزود الكمية اللي حكتبها تلقائي بالمنتج الخاص بهاي المواصفة  وبالستور بردو لانو الكمية تاعة المنتج الاساسي هو مجموع هدول الكميات تاعة مواصفاته 

                if(!empty($data['image'])){
                   if($request->hasFile('image')){
                       $file_path_original= MediaClass::store($request->file('image'),'product-attributes-images');//store productAttribute image
                       $data['image']=$file_path_original;
                   }else{
                       $data['image']=$productAttribute->image;
                   }
                  $data['image']= str_replace("public/","",$data['image']);
                 if($productAttribute->image){
                    //   dd($data['image']);
                     $productAttribute->image()->update(['url'=>$data['image'],'imageable_id'=>$productAttribute->id,'imageable_type'=>'Modules\ProductAttribute\Entities\ProductArrayAttribute']);
           
                 }else{
           
                     $productAttribute->image()->create(['url'=>$data['image'],'imageable_id'=>$productAttribute->id,'imageable_type'=>'Modules\ProductAttribute\Entities\ProductArrayAttribute']);
                 }
             }
            return $productAttribute;
            }

    
}
