<?php
namespace Modules\Cart\Repositories\Admin;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Cart\Repositories\Admin\CartRepositoryInterface;
use DB;
    use Illuminate\Support\Facades\Auth;
    use Modules\Product\Entities\Product;
    use Modules\Cart\Entities\Cart;
    use Modules\SubProduct\Entities\SubProduct;
    use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use App\Scopes\ActiveScope;
use Modules\Auth\Entities\User;

class CartRepository extends EloquentRepository implements CartRepositoryInterface
{
    public function all($model){
    $modelData=$model->get();
       return  $modelData;
   }

    public function getAllPaginates($model,$request){
    $modelData=$model->with(['productArrayAttributes.product','user'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
    }
    public function getAllProductArrayAttributesCartPaginates($model,$request,$id){
               $cart= $model->find($id);
               if(empty($cart)){
                   return 'هذه السلة غير موجودة بالنظام او ربما تكون غير مفعلة';
               }
               if(empty($cart->productArrayAttributes())){
                   return 'لا يوجد منتجات في هذه السلة';
               }
        return $cart->productArrayAttributes()->with(['product'])->paginate($request->total);
    }
   public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->with(['productArrayAttributes.product','user'])->paginate($request->total);
        return $modelData;
    }
  
    
}
