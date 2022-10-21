<?php
namespace Modules\UpSell\Repositories\Admin;

use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Auth\Entities\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\UpSell\Repositories\Admin\UpSellRepositoryInterface;
use DB;
    use Illuminate\Support\Facades\Auth;
use Modules\Product\Entities\Product;
use App\Scopes\ActiveScope;
use Modules\Upsell\Entities\Upsell;
class UpSellRepository extends EloquentRepository implements UpSellRepositoryInterface
{

     public function store($request,$model){
        $data=$request->validated();
        if(!empty($data['upsells'])){
            $productUpsells= Upsell::where('product_id',$data['product_id'])->first();
            if(!empty($productUpsells)){
                $resultExistProduct=  in_array($data['product_id'], $productUpsells->upsells);
                if($resultExistProduct==true){
                    return 'من فضلك لا تضع المنتج نفسه ضمن مبيعاته';
                }
                foreach($data['upsells'] as $sell){
                  $resultExistUpsells=  in_array($sell, $productUpsells->upsells);
                   if($resultExistUpsells){
                       return 'لا يمكنك اضافة مبيعات نفسها على المنتج نفسه اكثر من مرة ';
                   }
                }
                // return 'هذا المنتج تابع اليه مبيعات بالفعل لذلك لا يمكنك الا التعديل عليها ولا يمكن لك ان تضيف ';
                return 'لا يمكنك اضافة المنتج نفسه اكثر من مرة ';
        
            }

            $upsell=new $model;
            $upsell->product_id=$data['product_id'];
            $upsell->upsells=$data['upsells'];
            $upsell->name=$data['name'];
            $upsell->description=$data['description'];
            $upsell->footer=$data['footer'];
            $upsell->save();
            return $upsell;
        }else{
            return 'من فضلك اضف مبيعات لهذا المنتج';
        }
    
    }
         public function updateUpsellsPro($request,$id,$productId,$model){
            $data=$request->validated();
            if(!empty($data['upsells'])){
                $upsell= $model->where('id',$id)->first();
                if(!empty($upsell)){
                    $resultExistProduct=  in_array($upsell->product_id, $upsell->upsells);
                    // if($resultExistProduct==true){
                    //     return 'من فضلك لا تضع المنتج نفسه ضمن مبيعاته';
                    // }
                    foreach($data['upsells'] as $sell){
                      $resultExistUpsells=  in_array($sell, $upsell->upsells);
                       if($resultExistUpsells){
                           return 'لا يمكنك اضافة مبيعات نفسها على المنتج نفسه اكثر من مرة ';
                       }
                    $upsells=$upsell->upsells;
                    array_push($upsells,$sell);
                        $upsell->upsells=$upsells;
                    }
                }
    
                $upsell->name=$data['name'];
                $upsell->description=$data['description'];
                $upsell->footer=$data['footer'];
                $upsell->save();
                return $upsell->upsells;
                return $upsell;
            }
    
    }

    

    public function getUpsellsProduct($productId){
        $product=Product::where(['id'=>$productId])->first(); 
        if(empty($product)){
            return 'غير موجود بالنظام';
        }
        $upsellsProduct=[];
        
         //if this id in arr upsells not exist in products table , will delete it in all get for it , becuase not found in table products
                             
       $upsell= Upsell::where('product_id',$productId)->first();
      $upsells= $upsell->upsells;
        foreach( $upsells as $upsellId){
          $proUpsell=  Product::where(['id'=>$upsellId])->with(['productImages'])->first();
        //   dd($proUpsell);
          if(empty($proUpsell)){
                                                    //   unset($upsellId);

              array_splice($upsells, array_search($upsellId, $upsells ), 1);

            $upsell->upsells=$upsells;
            $upsell->save();
          }else{
              
           array_push($upsellsProduct,$proUpsell);
          }
        }
       return $upsellsProduct;

    }
   
   public function deleteUpsellProduct($model,$id,$upsellId){
       $upsell=$model->where(['id'=>$id])->first();
       if(empty($upsell)){
         return 'غير موجود بالنظام';
       }
       $upsells=$upsell->upsells;
      $result= in_array($upsellId,$upsells);
      if(!$result){
          return 'العنصر الذي تريد حذفه غير موجود بالفعل  هنا لحذفه';
      }

            array_splice($upsells, array_search($upsellId, $upsells ), 1);
                      $upsell->upsells=$upsells;
                      $upsell->save();

       return $upsells;
   }
}
