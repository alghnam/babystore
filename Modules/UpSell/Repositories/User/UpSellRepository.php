<?php
namespace Modules\UpSell\Repositories\User;

use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Auth\Entities\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\UpSell\Repositories\User\UpSellRepositoryInterface;
use DB;
    use Illuminate\Support\Facades\Auth;
use Modules\Product\Entities\Product;
use App\Scopes\ActiveScope;

class UpSellRepository extends EloquentRepository implements UpSellRepositoryInterface
{
          public function getAllPaginates($model,$request){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
     public function store($request,$model){
        $data=$request->validated();
    //  $product= Product::where(['id'=>$productId])->first();
      if(!empty($data['upsells'])){
          foreach($data['upsells'] as $sell){
                                  $upsellCount=$model->where(['product_id'=>$data['product_id'],'upsells'=>$sell])->count();
                    if($upsellCount!==0){
                        return 'لا يمكنك اضافة المنتج نفسه اكثر من مرة';
                    }
              $upsell=new $model;
              
              $upsell->product_id=$data['product_id'];
              $upsell->upsells=$sell;
              $upsell->name=$data['name'];
              $upsell->description=$data['description'];
              $upsell->footer=$data['footer'];
                $upsell->save();
              
          }
         
      //$product->similarProducts()->attach($data['similar']);
      }
      return 200;
    
    }

    
     public function updateUpsellsProduct($request,$productId,$model){
        $data=$request->validated();
                  $product= Product::where(['id'=>$productId])->first();
                foreach($data['upsells'] as $sell){
                    $upsellCount=$model->where(['product_id'=>$productId,'upsells'=>$sell])->count();
                    if($upsellCount!==0){
                        return 'لا يمكنك اضافة المنتج نفسه اكثر من مرة';
                    }
                    $upsell=new $model;
              $upsell->product_id=$productId;
              $upsell->upsells=$sell;
                $upsell->save();
                     // $product->upsellsProduct()->saveMany($data['upsells']);
    //   $product->upsellsProduct()->createMany([
    // new $model(['name' => 'A new comment.','upsells'=>$sell])
// ]);
                }
      
     
      return 200;
    
    }
    public function getUpsellsProduct($productId){
        $product=Product::where(['id'=>$productId])->first();
        if(empty($product)){
            return 'غير موجود بالنظام';
        }
        $upsells=[];
       foreach($product->upsellsProduct as $upsell){
           $proSell= Product::where(['id'=>$upsell->upsells])->first();
          array_push($upsells,$proSell);
       }
       return $upsells;

    }
        public function productAttrs($productId){
        $product=Product::where(['id'=>$productId])->first();
        if(empty($product)){
            return 'غير موجود بالنظام';
        }
        return $product->productArrayAttributes;
       

    }
         
   
   public function deleteUpsellProduct($model,$productId,$upsellId){
       $upsellPro=$model->where(['product_id'=>$productId,'upsells'=>$upsellId])->first();
     if(empty($upsellPro)){
         return 'غير موجود بالنظام';
     }
          $r= $model->find($upsellPro->id);

       $r->delete();
       return 200;
   }
}
