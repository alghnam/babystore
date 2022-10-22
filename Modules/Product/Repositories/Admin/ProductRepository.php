<?php
namespace Modules\Product\Repositories\Admin;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use Modules\Product\Entities\ProductImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Auth\Entities\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Entities\Cart;
use Modules\Product\Repositories\Admin\ProductRepositoryInterface;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
// use Location;
use DB;
use Stevebauman\Location\Facades\Location;


use MakiDizajnerica\GeoLocation\Facades\GeoLocation;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Modules\Order\Entities\Order;
use Modules\Category\Entities\Category;
use Modules\Order\Entities\ProductOrder;
use Modules\Search\Entities\Search;
use App\Scopes\ActiveScope;

class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
      public function search($model,$words){
    $modelData=$model->where(function ($query) use ($words) {
              $query->where('name', 'like', '%' . $words . '%');
         })->with('productImages')->get();
         return $modelData;
      }
            public function getProductsForSubCategoryTable($model,$subCategoryId){
                    if(auth()->guard('api')->user()==null){

        $modelData=$model->where('sub_category_id',$subCategoryId)->with(['subCategory','productImages'])->get();
                    }else{
        $modelData=$model->where('sub_category_id',$subCategoryId)->with(['subCategory','productImages','favorites'=> function ($hasMany) {
        $hasMany->where('user_id', auth()->guard('api')->user()->id);
    }])->get();
                        
                    }
          return  $modelData;
    } 
  public function searchForSimilars($model,$words){
    $modelData=$model->where(function ($query) use ($words) {
              $query->where('name', 'like', '%' . $words . '%');
         })->with(['productImages'])->get();
       return  $modelData;
   
    }

        
        public function mainCategoryProduct($id,$model){
          $item=$model->where('id',$id)->first();
        if(!empty($item)){
            $item=$model->findOrFail($id);
        }else{
            
            return 'غير موجود';
        }
        return $item->category;
    }  
    public function productAttributes($id,$model){
          $item=$model->where('id',$id)->first();
        if(!empty($item)){
            $item=$model->findOrFail($id);
        }else{
            return 'غير موجود';
        }
        return $item->productAttributes;
    }  
    public function productArrayAttributes($id,$model){
          $item=$model->where('product_id',$id)->with('image')->withoutGlobalScope(ActiveScope::class)->get();
          

        return $item;
    }
    
    public function find($id,$model){
      $item=  $model->find($id);
      if($item){
       $item->load(['category.mainCategory','category','subCategory','productAttributes','productArrayAttributes.image','productImages']);
     
      }else{
            // return __('not found');
            return 'غير موجود';
        }
        return $item;
    }  
    public function findImagesProduct($id,$model){
        $item=$model->where('id',$id)->first();
        if(!empty($item)){
            $item=$model->findOrFail($id);
            return $item->productImages;
        }else{
            
            return 'غير موجود';
        }
        return $item;
    }

    public function getAllPaginates($model,$request){
        $modelData=$model->with('category.mainCategory')->paginate($request->total);
       return  $modelData;
   
    }
       public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->with('category.mainCategory')->paginate($request->total);
        return $modelData;
    }


    // methods overrides
    public function store($request,$model){
        $data=$request->validated();
              if($data['original_price']==0){
            return 'يجب ان يتجاوز السعر الاصلي  : الصفر';
        }
                    if($data['price_discount_ends']==0){
            return 'يجب ان يتجاوز السعر النهائي بعد الخصم :  الصفر';
        }
        if($data['original_price']<$data['price_discount_ends']){
            return 'يجب ان يكون السعر النهائي بعد الخصم اقل من السعر الاصلي للمنتج';
        }
        
        $enteredData=  Arr::except($data ,['product_images']);

        $product= $model->create($enteredData);
        if($request->hasFile('product_images')){
            $filesProduct=[];
            $files= $request->file('product_images'); //upload file 
            foreach($files as $file){
                $file_path_original= MediaClass::store($file,'product-images');//store product images
                $data['product_images']=$file_path_original;
                $file_path_original= str_replace("public/","",$file_path_original);
                array_push($filesProduct,['filename'=>$file_path_original]);
            }

            $product->productImages()->createMany($filesProduct);
        }
            $product->load(['category.mainCategory','category','productAttributes','productArrayAttributes.image','productImages']);
            return $product;
    }
        public function update($request,$id,$model){

        $product=$this->find($id,$model);
        if(!empty($product)){
            
            $data= $request->validated();
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
            $product->update($enteredData);
            
    
        if($request->hasFile('product_images')){

            $files= $request->file('product_images'); //upload file 
            $filesProduct=[];
            foreach($files as $file){ 

                $file_path_original= MediaClass::store($file,'product-images');//store product images
                                    $file_path_original= str_replace("public/","",$file_path_original);

              array_push($filesProduct,['filename'=>$file_path_original]);

            }


             $product->productImages()->createMany($filesProduct);
 
 
        }

        }    

        return $product;
    }
    

    public function productsInInventory($model){
       $productsInInventory= $model->with('productAttributes')->get();
       return $productsInInventory;
    }

   
    public function deleteImage($idImage){
        
       $image= ProductImage::find($idImage);
       if($image){
       $image->delete();
       }else{
           
                        // return __('not found');
            return 'غير موجود بالنظام ';
                
       }
       return $image;
        
    }
    public function findE($id,$model){
        $item=$model->withoutGlobalScope(ActiveScope::class)->withTrashed()->where('id',$id)->first();
        if(empty($item)){
            return 'هذا العنصر غير موجود بالنظام';
        }
        return $item;
    }
     public function forceDelete($id,$model){
        //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
        $itemInTableitems = $this->findE($id,$model);//find this item from  table items
        if(is_string($itemInTableitems)){//this item not found in items table
            return $itemInTableitems;
        }
        
        $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
        if(is_string($itemInTrash)){//this item not found in trash items table
            return 'هذا العنصر غير موجود في سلة المحذوفات';
        }
            $itemInTrash->forceDelete();
            return 200;
    }


    
}
