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

// use Adrianorosa\GeoLocation\GeoLocation;

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
         })->get();
         return $modelData;
      }
            public function getProductsForSubCategoryTable($model,$subCategoryId){
            //               $category=  Category::where('id',$subCategoryId)->first();
            //               if($category->parnet_id==null){
            //               dd($category->parnet_id);
            //       return 400; //this category_id is main category , so cannt get this sub categrories from main category , only , can get it from sub category
            //   }
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
         })->get();
    //         $user=auth()->guard('api')->user();
    //             if($user==null){
    //                 //generate session id
    //                 $session_id=Storage::get('session_id');
    //                 if(empty($session_id)){
    //                     $session_id= Str::random(30);
    //                     Storage::put('session_id',$session_id);
    //                     Search::insert(['word'=>$words,'session_id'=>$session_id]);

    //                 }else{
    //                   Search::insert(['word'=>$words,'session_id'=>$session_id]);
    //                     $modelData=$model->where(function ($query) use ($words) {
    //           $query->where('name', 'like', '%' . $words . '%');
    //      })->with(['productImages'])->get();
                        
    //                 }
    //             }else{
    //                 Search::insert(['word'=>$words,'user_id'=>$user->id]);
    // $modelData=$model->where(function ($query) use ($words) {
    //           $query->where('name', 'like', '%' . $words . '%');
    //      })->with(['productImages','favorites'=> function ($hasMany) {
    //     $hasMany->where('user_id', auth()->guard('api')->user()->id);
    // }])->get();
                // }

       return  $modelData;
   
    }

        
        public function mainCategoryProduct($id,$model){
          $item=$model->where('id',$id)->first();
        if(!empty($item)){
            $item=$model->findOrFail($id);
        }else{
            
            // return __('not found');
            return 'غير موجود';
        }
        return $item->category;
    }  
    public function productAttributes($id,$model){
          $item=$model->where('id',$id)->first();
        if(!empty($item)){
            $item=$model->findOrFail($id);
        }else{
            // return __('not found');
            return 'غير موجود';
        }
        return $item->productAttributes;
    }  
    public function productArrayAttributes($id,$model){
          $item=$model->where('product_id',$id)->with('image')->withoutGlobalScope(ActiveScope::class)->get();
          

        return $item;
    }
    
    // public function similarsProduct($model,$id){
    //     $product=$model->find($id);
    //   return $product->similarProducts;
        
        
    // }
    public function find($id,$model){
      $item=  $model->find($id);
      if($item){
       $item->load(['category.mainCategory','category','productAttributes','productArrayAttributes','productImages']);
      // $item->load(['category.mainCategory','category','productAttributes','productArrayAttributes','productImages','similarProducts']);
     // $item->load(['category.mainCategory','category','productAttributes','productArrayAttributes.image','productImages'])->whereNot('id',$id);
          
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
            
            // return __('not found');
            return 'غير موجود';
        }
        return $item;
    }

    public function getAllPaginates($model,$request){
        //$modelData=$model->where('locale',config('app.locale'))->with('category.mainCategory')->paginate($request->total);
        $modelData=$model->with('category.mainCategory')->paginate($request->total);
       return  $modelData;
   
    }
    


    // methods overrides
    public function store($request,$model){
        $data=$request->validated();
        $data['locale']=Session::get('applocale');

        
        $enteredData=  Arr::except($data ,['product_images']);

        $product= $model->create($enteredData);
        if($request->hasFile('product_images')){
            // dd($data['product_images']);
            $filesProduct=[];
            $files= $request->file('product_images'); //upload file 
            // dd($files);
            foreach($files as $file){
                $file_path_original= MediaClass::store($file,'product-images');//store product images
                $data['product_images']=$file_path_original;
                dd($data['product_images']);
                $file_path_original= str_replace("public/","",$file_path_original);
                array_push($filesProduct,['filename'=>$file_path_original]);
            }
        //    dd($filesProduct);
        

            $product->productImages()->createMany($filesProduct);
        }
            $product->load(['category.mainCategory','category','productAttributes','productArrayAttributes.image','productImages']);
            return $product;
    }
        public function update($request,$id,$model){

        $product=$this->find($id,$model);
        if(!empty($product)){
            
            $data= $request->validated();
    
            $enteredData=  Arr::except($data ,['image']);
            dd($enteredData);
            $product->update($enteredData);
            
    
    
        //  if(!empty($data['image'])){
        //        if($request->hasFile('image')){
        //            $file_path_original= MediaClass::store($request->file('image'),'Product-images');//store Product image
        //            $data['image']=$file_path_original;
    
        //        }else{
        //            $data['image']=$product->image;
        //        }
        //      if($product->image){
        //         //   dd($data['image']);
        //          $product->image()->update(['url'=>$data['image'],'imageable_id'=>$product->id,'imageable_type'=>'Modules\Auth\Entities\Product']);
       
        //      }else{
       
        //          $product->image()->create(['url'=>$data['image'],'imageable_id'=>$product->id,'imageable_type'=>'Modules\Auth\Entities\Product']);
        //      }
        //  }
        if($request->hasFile('product_images')){
        // dd($data['product_images']);

            $files= $request->file('product_images'); //upload file 
            // dd($files);
            $filesProduct=[];
            foreach($files as $file){ 

                $file_path_original= MediaClass::store($file,'product-images');//store product images
                                    $file_path_original= str_replace("public/","",$file_path_original);

              array_push($filesProduct,['filename'=>$file_path_original]);
            //   if($product->productImages->count()!==0){
            //         foreach($product->productImages as $productImage){
            //             if($productImage->filename!==$file_path_original){
            //                 $product->productImages()->delete();
            //             }
            //         }   
            //     }
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

    public function forceDelete($id,$model){
        //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
        $itemInTableitems = $this->find($id,$model);//find this item from  table items
        if(empty($itemInTableitems)){//this item not found in items table
            $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
            if(empty($itemInTrash)){//this item not found in trash items
            // return __('not found');
            return 'غير  موجود هذا العنصر بسلة المحذوفات ';
            
                        }else{
                 MediaClass::delete($itemInTrash->image);
               
                $itemInTrash->forceDelete();
                
                return $itemInTrash;
            }
        }else{
                        // return __('not found');
            return 'غير موجود بالنظام ';
                }


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

    
}
