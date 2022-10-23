<?php
namespace Modules\SimilarProduct\Repositories\Admin;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\SimilarProduct\Repositories\Admin\SimilarProductRepositoryInterface;
use Modules\Product\Entities\Product;
use App\Scopes\ActiveScope;

class SimilarProductRepository extends EloquentRepository implements SimilarProductRepositoryInterface
{
     public function find($id,$model){
        $item=$model->withoutGlobalScope(ActiveScope::class)->withTrashed()->where('id',$id)->with(['productImages'])->first();
        if(empty($item)){
            return 'هذا العنصر غير موجود بالنظام';
        }
        return $item;
    }
        public function similarsProduct($productId,$model){
      $similarsProduct=  $model->where('product_id',$productId)->withoutGlobalScope(ActiveScope::class)->get();
        return $similarsProduct;
    }
    public function updateSimilar($request,$model,$productId){
        $data=$request->all();
      $product= Product::where(['id'=>$productId])->first();
      if(!empty($data['similar'])){
          foreach($data['similar'] as $simi){
                 $similarCount=$model->where(['product_id'=>$productId,'similar'=>$simi])->count();
                    if($similarCount!==0){
                        return 'لا يمكنك اضافة المنتج نفسه اكثر من مرة';
                    }
              $similar=new $model;
              $similar->product_id=$productId;
              $similar->similar=$simi;
                $similar->save();
              
          }
         
      //$product->similarProducts()->attach($data['similar']);
      }
      return $product->similarProducts;
    
    }
    public function destroySimilar($model,$productId,$similarId){
      $productSimilar= $model->where(['product_id'=>$productId,'similar'=>$similarId])->first();
      if($productSimilar===null){
          return 'غير موجود بالنظام';
      }else{
          
     $r= $model->find($productSimilar->id);
     
      $r->delete();

    return 200;
      }
    }
}
